<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddOn;
use Razorpay\Api\Api;

class AddOnMarketplaceController extends Controller
{
    private function getApi(): Api
    {
        return new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    }

    public function index()
    {
        $institute = auth()->user()->institute;
        $addOns = AddOn::where('is_active', true)->get();
        $purchasedIds = $institute->addOns()->pluck('add_ons.id')->toArray();

        return view('marketplace.index', compact('addOns', 'purchasedIds', 'institute'));
    }

    public function purchase(Request $request, AddOn $addOn)
    {
        $institute = auth()->user()->institute;

        if ($institute->hasAddOn($addOn->slug)) {
            return back()->with('error', 'You already own this powerup.');
        }

        try {
            $api = $this->getApi();

            $order = $api->order->create([
                'amount'          => (int) ($addOn->price * 100),
                'currency'        => 'INR',
                'receipt'         => 'addon_' . $institute->id . '_' . $addOn->id . '_' . time(),
                'notes'           => [
                    'institute_id' => $institute->id,
                    'addon_id'     => $addOn->id,
                    'type'         => 'Powerup Purchase',
                ],
                'payment_capture' => 1,
            ]);

            return view('subscription.checkout', [
                'order_id'  => $order->id,
                'amount'    => $order->amount,
                'currency'  => $order->currency,
                'key_id'    => config('services.razorpay.key_id'),
                'institute' => $institute,
                'user'      => auth()->user(),
                'callback_url' => route('marketplace.verify'),
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Could not initiate payment: ' . $e->getMessage());
        }
    }

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

            $order = $api->order->fetch($request->razorpay_order_id);
            $institute = auth()->user()->institute;
            $addOnId = $order->notes->addon_id;
            $addOn = AddOn::findOrFail($addOnId);

            $institute->addOns()->attach($addOn->id, [
                'purchased_at' => now(),
                'price_paid' => $addOn->price,
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ]);

            return redirect()->route('dashboard', ['slug' => $institute->slug])
                ->with('success', "Powerup Unlocked! {$addOn->name} is now active on your account. 🚀");

        } catch (\Exception $e) {
            return redirect()->route('marketplace.index', ['slug' => auth()->user()->institute->slug])
                ->with('error', 'Verification failed.');
        }
    }
}
