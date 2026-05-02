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

            @elseif($institute->subscription_expires_at && $institute->subscription_expires_at->isFuture())
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#059669,#10b981);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">✅</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Manual Plan Active</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">Your account is fully active until <strong>{{ $institute->subscription_expires_at->format('M d, Y') }}</strong>.</p>
                    <p style="opacity:0.7;font-size:0.75rem;margin-top:0.5rem;">Days remaining: {{ (int) ceil(now()->floatDiffInDays($institute->subscription_expires_at)) }}</p>
                </div>

            @elseif($isInTrial)
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#f59e0b,#f97316);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">⏳</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Free Trial Active — {{ $daysLeft }} day{{ $daysLeft == 1 ? '' : 's' }} remaining</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">Trial expires on {{ $trialEnds->format('M d, Y') }}. Subscribe to extend access.</p>
                    <div style="background:rgba(255,255,255,0.2);border-radius:999px;height:6px;margin-top:1rem;overflow:hidden;">
                        <div style="height:100%;background:white;border-radius:999px;width:{{ ($daysLeft / 14) * 100 }}%;transition:width 1s;"></div>
                    </div>
                </div>

            @else
                <div class="p-6 rounded-2xl" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:white;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem;">
                        <div style="font-size:2rem;">🔒</div>
                        <h3 style="font-size:1.25rem;font-weight:700;">Subscription Expired — Read-Only Mode</h3>
                    </div>
                    <p style="opacity:0.85;font-size:0.9rem;">Please make a one-time payment to renew your 30-day access.</p>
                </div>
            @endif

            <!-- Plan Choice -->
            @if(!$institute->is_lifetime_free)
                <div class="bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm p-12" x-data="{ 
                    customMonths: 7,
                    monthlyPrice: {{ \App\Models\Setting::get('monthly_price', 499) }},
                    discount: {{ \App\Models\Setting::get('bulk_discount_percentage', 20) }},
                    calculatePrice() {
                        let total = this.customMonths * this.monthlyPrice;
                        if (this.customMonths > 6) {
                            return Math.round(total - (total * this.discount / 100));
                        }
                        return total;
                    }
                }">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-3 uppercase tracking-tighter">
                            {{ ($institute->subscription_expires_at && $institute->subscription_expires_at->isFuture()) ? 'Extend Your Access' : 'Choose Your Growth Plan' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">One-time payment for full access. No hidden charges or auto-renewals.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Monthly Plan --}}
                        <div class="relative p-8 rounded-[2.5rem] border-2 border-gray-100 dark:border-gray-700 hover:border-indigo-500 transition-all group">
                            <div class="mb-6">
                                <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-widest">Flexible</span>
                                <h4 class="text-2xl font-black mt-4 uppercase tracking-tighter">1 Month</h4>
                                <div class="flex items-baseline gap-1 mt-2">
                                    <span class="text-4xl font-black">₹{{ \App\Models\Setting::get('monthly_price', 499) }}</span>
                                    <span class="text-xs text-gray-400 font-bold uppercase">/mo</span>
                                </div>
                            </div>
                            <ul class="space-y-4 mb-8">
                                @foreach(['Full platform access', 'All academic tools', 'Unlimited leads', 'Email alerts'] as $feat)
                                <li class="flex items-center gap-3 text-xs font-bold text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                    {{ $feat }}
                                </li>
                                @endforeach
                            </ul>
                            <form method="POST" action="{{ route('subscription.create') }}">
                                @csrf
                                <input type="hidden" name="months" value="1">
                                <button type="submit" class="w-full py-4 bg-gray-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">Select Monthly</button>
                            </form>
                        </div>

                        {{-- 6 Month Bundle --}}
                        <div class="relative p-8 rounded-[2.5rem] border-2 border-indigo-500 bg-indigo-50/30 dark:bg-indigo-900/10 shadow-2xl shadow-indigo-100 group">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest">Most Popular</div>
                            <div class="mb-6">
                                <h4 class="text-2xl font-black mt-4 uppercase tracking-tighter">6 Months</h4>
                                <div class="flex items-baseline gap-1 mt-2">
                                    <span class="text-4xl font-black">₹{{ \App\Models\Setting::get('six_month_price', 2499) }}</span>
                                    <span class="text-xs text-gray-400 font-bold uppercase">bundle</span>
                                </div>
                                <p class="text-[10px] text-emerald-600 font-black mt-2 uppercase tracking-widest">Save 15%</p>
                            </div>
                            <ul class="space-y-4 mb-8">
                                @foreach(['Everything in Monthly', 'Extended support', 'System verification', 'Premium Badge'] as $feat)
                                <li class="flex items-center gap-3 text-xs font-bold text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                    {{ $feat }}
                                </li>
                                @endforeach
                            </ul>
                            <form method="POST" action="{{ route('subscription.create') }}">
                                @csrf
                                <input type="hidden" name="months" value="6">
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:shadow-xl transition-all">Select Bundle</button>
                            </form>
                        </div>

                        {{-- Custom Enterprise --}}
                        <div class="relative p-8 rounded-[2.5rem] border-2 border-gray-100 dark:border-gray-700 hover:border-rose-500 transition-all group">
                            <div class="mb-6">
                                <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-3 py-1 rounded-full uppercase tracking-widest">Enterprise</span>
                                <h4 class="text-2xl font-black mt-4 uppercase tracking-tighter">Custom Duration</h4>
                                <div class="flex items-baseline gap-1 mt-2">
                                    <span class="text-4xl font-black" x-text="'₹' + calculatePrice().toLocaleString()">₹---</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">total</span>
                                </div>
                            </div>
                            
                            <div class="space-y-6 mb-8">
                                <div>
                                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">
                                        <span>Duration</span>
                                        <span class="text-rose-600" x-text="customMonths + ' Months'">7 Months</span>
                                    </div>
                                    <input type="range" min="2" max="12" x-model="customMonths" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-rose-500">
                                </div>
                                <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100" x-show="customMonths > 6">
                                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        {{ \App\Models\Setting::get('bulk_discount_percentage', 20) }}% Bulk Discount Applied
                                    </p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('subscription.create') }}">
                                @csrf
                                <input type="hidden" name="months" :value="customMonths">
                                <button type="submit" class="w-full py-4 bg-gray-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all">Buy Custom Plan</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Billing History --}}
            <div class="bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm p-12 mt-12 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Billing History</h3>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $institute->payments->count() }} Invoices</span>
                </div>

                @if($institute->payments->count() > 0)
                <div class="overflow-x-auto -mx-12 px-12">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700">
                                <th class="pb-6">Date</th>
                                <th class="pb-6">Transaction ID</th>
                                <th class="pb-6">Plan</th>
                                <th class="pb-6">Amount</th>
                                <th class="pb-6 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach($institute->payments as $payment)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-all">
                                <td class="py-6 pr-4">
                                    <p class="text-xs font-black text-gray-900 dark:text-white">{{ $payment->created_at->format('M d, Y') }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $payment->created_at->format('H:i A') }}</p>
                                </td>
                                <td class="py-6 pr-4">
                                    <code class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">{{ $payment->razorpay_payment_id }}</code>
                                </td>
                                <td class="py-6 pr-4">
                                    <p class="text-xs font-black text-gray-700 dark:text-gray-300">{{ $payment->plan_name }}</p>
                                    <p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest">Valid until {{ $payment->expires_at->format('M d, Y') }}</p>
                                </td>
                                <td class="py-6 pr-4">
                                    <span class="text-xs font-black text-gray-900 dark:text-white">₹{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full uppercase tracking-widest">Paid</span>
                                        <a href="{{ route('subscription.invoice', $payment->id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm group/btn" title="Download Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No payment records found</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
