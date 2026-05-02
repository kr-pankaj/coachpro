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
    public function create()
    {
        $institute = auth()->user()->institute;

        try {
            $api = $this->getApi();

            // Amount in paise (₹1,999 × 100 = 199900 paise)
            $order = $api->order->create([
                'amount'          => 199900,
                'currency'        => 'INR',
                'receipt'         => 'rcpt_' . $institute->id . '_' . time(),
                'notes'           => [
                    'institute_id'   => $institute->id,
                    'institute_name' => $institute->name,
                ],
                'payment_capture' => 1, // auto-capture
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
            // Store payment reference (reusing the subscription_id column for now)
            $institute->razorpay_subscription_id = $request->razorpay_payment_id;
            $institute->save();

            return redirect()->route('dashboard')
                ->with('success', 'Payment successful! Your account is now active. 🎉');

        } catch (\Exception $e) {
            return redirect()->route('subscription.index')
                ->with('error', 'Payment verification failed. Please contact support with Payment ID: ' . $request->razorpay_payment_id);
        }
    }
}
