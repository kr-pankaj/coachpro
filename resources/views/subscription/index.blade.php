<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subscription & Billing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-xl text-green-800 dark:text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl text-red-800 dark:text-red-300 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Current Status Card -->
            @php
                $trialEnds = $institute->created_at->addDays(14);
                $isInTrial = $trialEnds->isFuture();
                $daysLeft  = $isInTrial ? (int) ceil(now()->floatDiffInDays($trialEnds)) : 0;
            @endphp

            @if($institute->is_lifetime_free)
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#7c3aed,#4f46e5);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">🎉</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Lifetime Free Access</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">You have been granted permanent free access to CoachPro. No subscription required!</p>
                </div>

            @elseif($institute->razorpay_subscription_id)
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#059669,#10b981);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">✅</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Active Subscription</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">Your subscription is active. Auto-debit is enabled via Razorpay.</p>
                    <p style="opacity:0.7;font-size:0.75rem;margin-top:0.5rem;">Subscription ID: {{ $institute->razorpay_subscription_id }}</p>
                </div>

            @elseif($isInTrial)
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#f59e0b,#f97316);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">⏳</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Free Trial Active — {{ $daysLeft }} day{{ $daysLeft == 1 ? '' : 's' }} remaining</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">Trial expires on {{ $trialEnds->format('M d, Y') }}. Subscribe before it ends to avoid interruption.</p>
                    <div style="background:rgba(255,255,255,0.2);border-radius:999px;height:6px;margin-top:1rem;overflow:hidden;">
                        <div style="height:100%;background:white;border-radius:999px;width:{{ ($daysLeft / 14) * 100 }}%;transition:width 1s;"></div>
                    </div>
                </div>

            @else
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">🔒</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Trial Expired — Read-Only Mode</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">You cannot create or edit data until you subscribe. Your existing data is safe.</p>
                </div>
            @endif

            <!-- Plan Choice -->
            @if(!$institute->is_lifetime_free && !$institute->razorpay_subscription_id)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Activate Your Account</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">One-time payment to unlock all features. Secure checkout via Razorpay.</p>

                    <div style="border:2px solid #4f46e5;border-radius:1rem;padding:1.75rem;background:#f5f3ff;max-width:380px;margin:0 auto 1.5rem;" class="dark:bg-indigo-950">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.25rem;">
                            <div>
                                <p style="font-size:0.75rem;color:#4f46e5;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.35rem;">One-Time Activation</p>
                                <p style="font-size:2.5rem;font-weight:800;color:#0f172a;line-height:1;" class="dark:text-white">₹1,999</p>
                                <p style="font-size:0.8rem;color:#64748b;margin-top:0.25rem;">Pay once, use forever</p>
                            </div>
                            <span style="background:#4f46e5;color:white;font-size:0.7rem;padding:0.25rem 0.75rem;border-radius:999px;font-weight:600;white-space:nowrap;">TEST MODE</span>
                        </div>

                        <ul style="list-style:none;padding:0;margin-bottom:1.5rem;">
                            @foreach(['Unlimited students & batches', 'Attendance tracking', 'Fee management', 'Student self-registration portal', 'Institute settings & configuration', 'Priority support'] as $f)
                            <li style="display:flex;align-items:center;gap:0.625rem;font-size:0.875rem;color:#374151;margin-bottom:0.6rem;" class="dark:text-gray-300">
                                <svg width="15" height="15" fill="none" stroke="#4f46e5" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>

                        <form method="POST" action="{{ route('subscription.create') }}">
                            @csrf
                            <button type="submit"
                                style="width:100%;padding:0.875rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;border:none;border-radius:0.75rem;font-weight:700;cursor:pointer;font-size:0.95rem;display:flex;align-items:center;justify-content:center;gap:0.5rem;transition:all 0.2s;"
                                onmouseover="this.style.opacity='0.9';this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.opacity='1';this.style.transform='translateY(0)'">
                                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="13" rx="2"/><path d="M3 10h18" stroke-linecap="round"/></svg>
                                Pay ₹1,999 via Razorpay
                            </button>
                        </form>
                    </div>

                    <div style="background:#fef9c3;border:1px solid #fcd34d;border-radius:0.75rem;padding:0.875rem 1rem;text-align:left;">
                        <p style="font-size:0.75rem;font-weight:700;color:#92400e;margin:0 0 0.5rem;">🧪 Razorpay Test Credentials</p>
                        <table style="font-size:0.75rem;color:#78350f;border-collapse:collapse;width:100%;">
                            <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;white-space:nowrap;">Card No.</td><td><strong>4718 6008 1099 0683</strong> (Visa Indian)</td></tr>
                            <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;">Expiry</td><td>Any future date &nbsp;e.g. 12/26</td></tr>
                            <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;">CVV</td><td><strong>100</strong></td></tr>
                            <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;">OTP</td><td><strong>1234</strong></td></tr>
                        </table>
                        <p style="font-size:0.72rem;color:#92400e;margin:0.5rem 0 0;">💡 Or select <strong>UPI</strong> and enter: <code>success@razorpay</code></p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
