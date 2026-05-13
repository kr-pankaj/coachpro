<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight uppercase">
                Platform <span class="text-rose-500">Configuration</span>
            </h2>
            <div class="px-4 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-full">
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">v1.0.6</span>
            </div>
        </div>
    </x-slot>

    <style>
        /* Pure CSS Toggle */
        .switch { position: relative; display: inline-block; width: 60px; height: 34px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e5e7eb; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input:checked + .slider { background-color: #10b981; }
        input:checked + .slider:before { transform: translateX(26px); }
        .dark .slider { background-color: #374151; }

        .config-card { background: white; border-radius: 2.5rem; padding: 3rem; border: 1px solid #f3f4f6; box-shadow: 0 20px 50px rgba(0,0,0,0.02); }
        .dark .config-card { background: #111827; border-color: #1f2937; }
        .input-pill { background: #f9fafb; border: none; border-radius: 1.25rem; padding: 1.25rem 1.5rem; font-weight: 700; width: 100%; transition: all 0.2s; }
        .dark .input-pill { background: #1f2937; color: white; }
        .input-pill:focus { ring: 2px; ring-color: #f43f5e; background: white; }
        .dark .input-pill:focus { background: #111827; }
    </style>

    <div class="py-12 bg-gray-50/30 dark:bg-gray-950 min-h-screen">
        <div class="max-w-6xl mx-auto px-6">
            
            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-500 text-white rounded-2xl font-bold text-sm text-center shadow-lg animate-bounce">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('superadmin.settings.update') }}" method="POST">
                @csrf

                <div class="config-card relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-rose-500"></div>
                    
                    <div class="flex items-center gap-4 mb-12">
                        <div class="w-1.5 h-8 bg-rose-500 rounded-full"></div>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">Platform Configuration</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                        {{-- Base Pricing --}}
                        <div>
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-3.67 3.58c-.029.23-.006.447.191.562l1.052.619c.163.096.359.056.475-.095.184-.24.39-.41.617-.527V9.303c.138.054.277.12.414.199.311.18.503.451.503.798 0 .306-.158.55-.423.729a4.426 4.426 0 00-.361.255L7.482 12.3c-.371.248-.596.647-.596 1.091 0 .737.487 1.303 1.114 1.543V15a1 1 0 102 0v-.092a4.535 4.535 0 003.67-3.58c.029-.23.006-.447-.191-.562l-1.052-.619c-.163-.096-.359-.056-.475.095a1.168 1.168 0 01-.617.527V10.697c.138-.054.277-.12.414-.199.311-.18.503-.451.503-.798 0-.306-.158-.55-.423-.729a4.426 4.426 0 00-.361-.255L12.518 7.7c.371-.248.596-.647.596-1.091 0-.737-.487-1.303-1.114-1.543V5z" clip-rule="evenodd"/></svg>
                                </div>
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Base Pricing</h4>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1">Monthly Price (₹)</label>
                                    <input type="number" name="settings[subscription_price]" value="{{ $settings['subscription_price'] ?? '999' }}" class="input-pill">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1">6-Month Bundle (₹)</label>
                                    <input type="number" name="settings[six_month_price]" value="{{ $settings['six_month_price'] ?? '4999' }}" class="input-pill">
                                </div>
                            </div>
                        </div>

                        {{-- Discounts & Offers --}}
                        <div>
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-900/40 flex items-center justify-center text-rose-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.354 5 5zm4 1V4a1 1 0 011 1v1H9z" clip-rule="evenodd"/><path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"/></svg>
                                </div>
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Discounts & Offers</h4>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1">Custom Plan Discount (%)</label>
                                    <input type="number" name="settings[bulk_discount_percentage]" value="{{ $settings['bulk_discount_percentage'] ?? '20' }}" class="input-pill">
                                    <p class="mt-3 text-[9px] font-black text-gray-400 uppercase tracking-widest pl-1 italic">Applied for plans > 6 months</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-16 flex justify-end">
                        <button type="submit" class="px-8 py-4 bg-[#0a0a0b] text-white rounded-full font-black text-[11px] uppercase tracking-widest flex items-center gap-3 hover:bg-black transition-all shadow-xl shadow-gray-200 dark:shadow-none hover:scale-105 active:scale-95 no-loader">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Save Platform Rules
                        </button>
                    </div>
                </div>

                {{-- Payment Availability --}}
                <div class="mt-10 config-card relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center text-amber-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">Payment Availability</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Globally enable or disable all platform transactions.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-800 p-4 rounded-3xl border border-gray-100 dark:border-gray-700">
                            <span id="toggle-label-v2" class="text-[10px] font-black uppercase tracking-widest {{ ($settings['payments_enabled'] ?? '1') == '1' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ ($settings['payments_enabled'] ?? '1') == '1' ? 'Active' : 'Disabled' }}
                            </span>
                            <label class="switch">
                                <input type="hidden" name="settings[payments_enabled]" value="0">
                                <input type="checkbox" name="settings[payments_enabled]" value="1" 
                                    {{ ($settings['payments_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                    onchange="document.getElementById('toggle-label-v2').innerText = this.checked ? 'Active' : 'Disabled'; 
                                              document.getElementById('toggle-label-v2').className = 'text-[10px] font-black uppercase tracking-widest ' + (this.checked ? 'text-emerald-600' : 'text-rose-600');">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-10 p-8 bg-gray-50 dark:bg-gray-800 rounded-3xl space-y-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Global Maintenance Message</label>
                        <textarea name="settings[payment_disabled_message]" rows="2" 
                            class="input-pill bg-white dark:bg-gray-900"
                            placeholder="Professional message for users...">{{ $settings['payment_disabled_message'] ?? 'We are currently upgrading our systems. Payments will resume shortly.' }}</textarea>
                    </div>
                </div>

                {{-- Contact Details --}}
                <div class="mt-10 config-card relative overflow-hidden mb-20">
                    <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-950 dark:text-white uppercase tracking-tight">Global Support Hub</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1">Support Email</label>
                            <input type="email" name="settings[support_email]" value="{{ $settings['support_email'] ?? 'support@quonixai.com' }}" class="input-pill">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1">Support Phone</label>
                            <input type="text" name="settings[support_phone]" value="{{ $settings['support_phone'] ?? '+91 00000 00000' }}" class="input-pill">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
