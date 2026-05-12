<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Payment History') }}
            </h2>
            <a href="{{ route('fees.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Back to Fees
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Student Summary Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-600 text-white flex items-center justify-center text-2xl font-black">
                            {{ substr($fee->student->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $fee->student->name }}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $fee->student->batch?->name ?? 'No Batch' }}</span>
                                <span class="text-xs text-gray-400 font-bold italic">Billing for {{ $fee->month_year }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        @if($fee->status !== 'pending')
                            <a href="{{ route('fees.receipt', $fee) }}" target="_blank" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center gap-2">
                                <x-icons.download class="w-4 h-4" />
                                Receipt
                            </a>
                        @endif
                        @php
                            $phoneNumber = $fee->student->parent_phone ?? $fee->student->phone;
                            $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
                            if(strlen($cleanPhone) == 10) $cleanPhone = "91" . $cleanPhone;
                            $shareUrl = route('fees.share', $fee->share_token);
                            $waMsg = "Fee status for " . $fee->student->name . " (Month: " . $fee->month_year . "): Total ₹" . number_format($fee->total_amount) . ", Paid ₹" . number_format($fee->paid_amount) . ", Due ₹" . number_format($fee->due_amount) . ". View receipt: " . $shareUrl;
                        @endphp
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waMsg) }}" target="_blank" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .01 5.403.006 12.039a11.81 11.81 0 001.578 5.925L0 24l6.135-1.612a11.771 11.771 0 005.911 1.577h.005c6.637 0 12.042-5.405 12.046-12.041a11.82 11.82 0 00-3.533-8.527"/></svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left: Payment Timeline --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest">Transaction Timeline</h4>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-tighter">{{ count($fee->payments) }} Installments Recorded</span>
                        </div>

                        <div class="space-y-8 relative before:absolute before:left-6 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-50 dark:before:bg-gray-700">
                            @forelse($fee->payments as $payment)
                                <div class="relative pl-14">
                                    <div class="absolute left-4 top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-600 z-10"></div>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-lg font-black text-gray-900 dark:text-white">₹{{ number_format($payment->amount) }}</p>
                                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tight mt-0.5">{{ $payment->payment_method ?? 'Payment' }}</p>
                                            @if($payment->remarks)
                                                <p class="text-xs text-gray-500 italic mt-2 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-xl border border-gray-100 dark:border-gray-700 inline-block">"{{ $payment->remarks }}"</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <p class="text-sm text-gray-400 italic">No payments recorded yet for this fee record.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right: Status & Add Payment --}}
                <div class="space-y-6">
                    {{-- Financial Stats --}}
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-lg shadow-indigo-200 dark:shadow-none">
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Total Course Fee</p>
                                <p class="text-3xl font-black mt-1">₹{{ number_format($fee->total_amount) }}</p>
                            </div>
                            @if($fee->discount_amount > 0)
                                <div class="flex justify-between items-center bg-indigo-700/50 p-3 rounded-2xl">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Discount Applied</span>
                                    <span class="text-sm font-black text-white">- ₹{{ number_format($fee->discount_amount) }}</span>
                                </div>
                            @endif
                            <div class="h-px bg-indigo-500/50"></div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Amount Paid</p>
                                    <p class="text-xl font-black mt-1">₹{{ number_format($fee->paid_amount) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-200">Balance Due</p>
                                    <p class="text-xl font-black mt-1 text-rose-100">₹{{ number_format($fee->due_amount) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Add Installment Form --}}
                    @if($fee->due_amount > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-6">Record New Installment</h4>
                            <form action="{{ route('fees.payments.store', $fee) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Amount (₹)</label>
                                    <input type="number" name="amount" step="0.01" max="{{ $fee->due_amount }}" required class="block w-full mt-1 border-gray-100 dark:border-gray-700 rounded-2xl p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. {{ number_format($fee->due_amount) }}">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Payment Method</label>
                                    <select name="payment_method" class="block w-full mt-1 border-gray-100 dark:border-gray-700 rounded-2xl p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="Cash">Cash</option>
                                        <option value="GPay">Google Pay</option>
                                        <option value="PhonePe">PhonePe</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Transaction Date</label>
                                    <input type="date" name="payment_date" value="{{ now()->toDateString() }}" required class="block w-full mt-1 border-gray-100 dark:border-gray-700 rounded-2xl p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Remarks</label>
                                    <input type="text" name="remarks" class="block w-full mt-1 border-gray-100 dark:border-gray-700 rounded-2xl p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. 2nd Installment">
                                </div>
                                <button type="submit" class="w-full py-4 bg-gray-900 dark:bg-gray-200 text-white dark:text-gray-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-800 transition-all mt-4">
                                    Record Payment
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-emerald-50 border border-emerald-100 rounded-[2.5rem] p-8 text-center">
                            <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                            </div>
                            <h4 class="text-emerald-800 font-black uppercase tracking-widest text-sm">Fully Settled</h4>
                            <p class="text-emerald-600 text-xs mt-2 font-bold italic">No outstanding balance remains for this record.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
