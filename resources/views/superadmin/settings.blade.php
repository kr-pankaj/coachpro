<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight uppercase tracking-tighter">
                Platform <span class="text-quonix-purple">Command Center</span>
            </h2>
            <div class="flex items-center gap-2 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-full border border-indigo-100 dark:border-indigo-800">
                <div class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></div>
                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">v1.0.6 Stable</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 dark:bg-gray-950">
        <div class="max-w-6xl mx-auto px-6">
            
            @if (session('success'))
                <div class="mb-8 p-5 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5"/></svg>
                    </div>
                    <p class="text-sm font-black text-emerald-800 tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-10">
                @csrf

                {{-- Platform Configuration Section --}}
                <div class="bg-white dark:bg-gray-900 rounded-[3.5rem] shadow-2xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-800 p-12 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-rose-500"></div>
                    
                    <div class="flex items-center gap-4 mb-14">
                        <div class="w-3 h-8 bg-rose-500 rounded-full"></div>
                        <h3 class="text-2xl font-black text-gray-950 dark:text-white uppercase tracking-tighter">Platform Configuration</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                        {{-- Base Pricing --}}
                        <div class="space-y-10">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Base Pricing</h4>
                            </div>

                            <div class="space-y-8 pl-2">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Monthly Price (₹)</label>
                                    <input type="number" name="settings[subscription_price]" value="{{ $settings['subscription_price'] ?? '999' }}" 
                                        class="w-full max-w-sm px-6 py-5 bg-gray-50 dark:bg-gray-800 border-none rounded-3xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-900 dark:text-white shadow-inner">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">6-Month Bundle (₹)</label>
                                    <input type="number" name="settings[six_month_price]" value="{{ $settings['six_month_price'] ?? '4999' }}" 
                                        class="w-full max-w-sm px-6 py-5 bg-gray-50 dark:bg-gray-800 border-none rounded-3xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-900 dark:text-white shadow-inner">
                                </div>
                            </div>
                        </div>

                        {{-- Discounts & Offers --}}
                        <div class="space-y-10">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-900/40 flex items-center justify-center text-rose-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Discounts & Offers</h4>
                            </div>

                            <div class="space-y-8 pl-2">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Custom Plan Discount (%)</label>
                                    <input type="number" name="settings[bulk_discount_percentage]" value="{{ $settings['bulk_discount_percentage'] ?? '20' }}" 
                                        class="w-full max-w-sm px-6 py-5 bg-gray-50 dark:bg-gray-800 border-none rounded-3xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-900 dark:text-white shadow-inner">
                                    <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Applied for plans > 6 months</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 flex justify-end">
                        <button type="submit" class="px-12 py-5 bg-gray-950 text-white rounded-full font-black text-xs uppercase tracking-widest flex items-center gap-3 hover:bg-indigo-600 transition-all shadow-xl shadow-gray-200 dark:shadow-none hover:scale-105 active:scale-95 no-loader">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                            Save Platform Rules
                        </button>
                    </div>
                </div>

                {{-- Payment Availability --}}
                <div class="bg-white dark:bg-gray-900 rounded-[3.5rem] shadow-2xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-800 p-12">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center text-amber-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tighter">Payment Availability</h3>
                                <p class="text-xs text-gray-400 font-bold mt-1">Globally enable or disable all platform transactions.</p>
                            </div>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="settings[payments_enabled]" value="0">
                            <input type="checkbox" name="settings[payments_enabled]" value="1" class="sr-only peer" {{ ($settings['payments_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-20 h-10 bg-gray-100 peer-focus:outline-none rounded-full peer dark:bg-gray-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[6px] after:left-[6px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500 shadow-inner"></div>
                            <span class="ml-4 text-xs font-black text-gray-500 uppercase tracking-widest peer-checked:text-emerald-600">{{ ($settings['payments_enabled'] ?? '1') == '1' ? 'Active' : 'Disabled' }}</span>
                        </label>
                    </div>

                    <div class="mt-10 p-8 bg-gray-50 dark:bg-gray-800/50 rounded-3xl space-y-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Global Maintenance Message</label>
                        <textarea name="settings[payment_disabled_message]" rows="2" 
                            class="w-full px-6 py-4 bg-white dark:bg-gray-800 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-600 dark:text-gray-300"
                            placeholder="Professional message for users...">{{ $settings['payment_disabled_message'] ?? 'We are currently upgrading our systems. Payments will resume shortly.' }}</textarea>
                    </div>
                </div>

                {{-- Contact Details --}}
                <div class="bg-white dark:bg-gray-900 rounded-[3.5rem] shadow-2xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-800 p-12">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tighter">Global Contact Hub</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Support Email Address</label>
                            <input type="email" name="settings[support_email]" value="{{ $settings['support_email'] ?? 'support@quonixai.com' }}" 
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-900 dark:text-white shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Support Phone Number</label>
                            <input type="text" name="settings[support_phone]" value="{{ $settings['support_phone'] ?? '+91 00000 00000' }}" 
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-900 dark:text-white shadow-inner">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
