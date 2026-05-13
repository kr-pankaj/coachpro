<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                Platform <span class="text-quonix-purple">Settings</span>
            </h2>
            <div class="flex items-center gap-2 px-4 py-1.5 bg-quonix-purple/10 rounded-full">
                <div class="w-2 h-2 bg-quonix-purple rounded-full animate-pulse"></div>
                <span class="text-[10px] font-black text-quonix-purple uppercase tracking-widest">Command Center</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                    </div>
                    <p class="text-sm font-bold text-emerald-600">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Payment Configuration --}}
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-quonix-purple/5 border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-quonix-purple/10 flex items-center justify-center text-quonix-purple">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            Payment & Subscription Control
                        </h3>
                    </div>

                    <div class="p-8 space-y-8">
                        {{-- Payments Toggle --}}
                        <div class="flex items-center justify-between p-6 bg-gray-50 dark:bg-gray-900/50 rounded-3xl border border-gray-100 dark:border-gray-800">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Enable Global Payments</h4>
                                <p class="text-xs text-gray-500 mt-1">If disabled, no institute will be able to subscribe or pay fees.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="settings[payments_enabled]" value="0">
                                <input type="checkbox" name="settings[payments_enabled]" value="1" class="sr-only peer" {{ ($settings['payments_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-quonix-purple shadow-inner"></div>
                            </label>
                        </div>

                        {{-- Subscription Price --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Default Subscription Price (₹)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold">₹</span>
                                    </div>
                                    <input type="number" name="settings[subscription_price]" value="{{ $settings['subscription_price'] ?? '999' }}" 
                                        class="w-full pl-10 pr-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-quonix-purple focus:border-quonix-purple font-bold text-gray-900 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Currency Code</label>
                                <input type="text" name="settings[currency]" value="{{ $settings['currency'] ?? 'INR' }}" 
                                    class="w-full px-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-quonix-purple focus:border-quonix-purple font-bold text-gray-900 dark:text-white uppercase">
                            </div>
                        </div>

                        {{-- Maintenance Message --}}
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Payment Disabled Message</label>
                            <textarea name="settings[payment_disabled_message]" rows="3" 
                                class="w-full px-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-quonix-purple focus:border-quonix-purple font-medium text-gray-600 dark:text-gray-300"
                                placeholder="We are temporarily not accepting payments. Please try again later.">{{ $settings['payment_disabled_message'] ?? 'We are currently upgrading our payment systems. Online payments will resume shortly. Thank you for your patience!' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Branding & Platform --}}
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-quonix-purple/5 border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            Platform Identity
                        </h3>
                    </div>

                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Support Email</label>
                                <input type="email" name="settings[support_email]" value="{{ $settings['support_email'] ?? 'support@quonixai.com' }}" 
                                    class="w-full px-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-quonix-purple focus:border-quonix-purple font-bold text-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Admin Contact Number</label>
                                <input type="text" name="settings[support_phone]" value="{{ $settings['support_phone'] ?? '+91 99999 99999' }}" 
                                    class="w-full px-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-quonix-purple focus:border-quonix-purple font-bold text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-10 py-5 bg-gradient-to-r from-quonix-purple to-quonix-magenta text-white font-black rounded-[2rem] shadow-2xl shadow-quonix-purple/30 hover:scale-105 active:scale-95 transition-all text-sm uppercase tracking-widest no-loader">
                        Apply Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
