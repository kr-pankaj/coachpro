<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    private function getApi(): Api
    {
        return new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    }

    public function index()
    {
        $institute = auth()->user()->institute;
        return view('subscription.index', compact('institute'));
    }

    /**
     * Create a one-time Razorpay order (test-friendly, no Plan ID needed).
     */
    public function create(Request $request)
    {
        // 1. GLOBAL SECURITY CHECK
        $paymentsEnabled = \App\Models\Setting::get('payments_enabled', '1') == '1';
        if (!$paymentsEnabled) {
            $msg = \App\Models\Setting::get('payment_disabled_message', 'Online payments are currently paused for system upgrades.');
            return back()->with('error', $msg);
        }

        $institute = auth()->user()->institute;
        $months = (int) ($request->months ?? 1);
        
        // Ensure valid range
        if ($months < 1) $months = 1;
        if ($months > 12) $months = 12;

        try {
            $api = $this->getApi();

            // Pricing logic from Command Center
            $basePrice = (int) \App\Models\Setting::get('subscription_price', 999);
            $sixMonthPrice = (int) \App\Models\Setting::get('six_month_price', 4999);
            $bulkDiscount = (int) \App\Models\Setting::get('bulk_discount_percentage', 20);

            $finalAmount = 0;
            if ($months === 1) {
                $finalAmount = $basePrice;
            } elseif ($months === 6) {
                $finalAmount = $sixMonthPrice;
            } else {
                // Custom calculation
                $totalBase = $basePrice * $months;
                if ($months > 6) {
                    $discountAmount = ($totalBase * $bulkDiscount) / 100;
                    $finalAmount = $totalBase - $discountAmount;
                } else {
                    $finalAmount = $totalBase;
                }
            }

            // Amount in paise
            $amountInPaise = (int) ($finalAmount * 100);

            $order = $api->order->create([
                'amount'          => $amountInPaise,
                'currency'        => 'INR',
                'receipt'         => 'rcpt_' . $institute->id . '_' . time(),
                'notes'           => [
                    'institute_id'   => $institute->id,
                    'months'         => $months,
                    'plan_type'      => $months . ' Month Plan',
                ],
                'payment_capture' => 1,
            ]);

            return view('subscription.checkout', [
                'order_id'  => $order->id,
                'amount'    => $order->amount,   // in paise
                'currency'  => $order->currency,
                'key_id'    => config('services.razorpay.key_id'),
                'institute' => $institute,
                'user'      => auth()->user(),
            ]);

        } catch (\Exception $e) {
            $msg = is_string($e->getMessage()) ? $e->getMessage() : json_encode($e->getMessage());
            return back()->with('error', 'Could not initiate payment: ' . $msg);
        }
    }

    /**
     * Verify the Razorpay payment signature and activate the institute.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        try {
            $api = $this->getApi();

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $institute = auth()->user()->institute;
            
            // Get order details to find out how many months were purchased
            $order = $api->order->fetch($request->razorpay_order_id);
            $months = (int) ($order->notes->months ?? 1);

            // If already active, extend from current expiry, otherwise start from now
            $startFrom = ($institute->subscription_expires_at && $institute->subscription_expires_at->isFuture()) 
                ? $institute->subscription_expires_at 
                : now();

            $institute->subscription_expires_at = $startFrom->addDays(30 * $months);
            $institute->plan_name = $months . ' Month Plan';
            $institute->razorpay_subscription_id = $request->razorpay_payment_id;
            $institute->save();

            // Record Payment History
            \App\Models\SubscriptionPayment::create([
                'institute_id' => $institute->id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'amount' => ($order->amount / 100),
                'months' => $months,
                'plan_name' => $institute->plan_name,
                'expires_at' => $institute->subscription_expires_at,
            ]);

            // Send Invoice Email
            try {
                \Illuminate\Support\Facades\Mail::to($institute->contact_email ?? auth()->user()->email)
                    ->send(new \App\Mail\SubscriptionInvoice(
                        $institute,
                        $institute->plan_name,
                        $request->razorpay_payment_id,
                        ($order->amount / 100), // convert paise to rupees
                        $institute->subscription_expires_at
                    ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Invoice Mail Failed: ' . $e->getMessage());
            }

            return redirect()->route('dashboard', ['slug' => $institute->slug])
                ->with('success', 'Payment successful! Your account has been extended by ' . ($months * 30) . ' days. 🎉');

        } catch (\Exception $e) {
            return redirect()->route('subscription.index')
                ->with('error', 'Payment verification failed. Please contact support with Payment ID: ' . $request->razorpay_payment_id);
        }
    }

    public function showInvoice($id)
    {
        $payment = \App\Models\SubscriptionPayment::where('id', $id)
            ->where('institute_id', auth()->user()->institute_id)
            ->firstOrFail();

        return view('subscription.invoice', compact('payment'));
    }
}
