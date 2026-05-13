<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                    Powerup<br>Marketplace
                </h2>
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mt-1">Plug-n-Play Feature Management</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Global Store</p>
                    <p class="text-xs font-black text-gray-900 dark:text-white uppercase italic">{{ $addOns->count() }} Active Powerups</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-8 sm:px-10 lg:px-12 space-y-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Create New Powerup --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-700 shadow-sm sticky top-32">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Forge New Powerup</h3>
                        </div>

                        <form action="{{ route('superadmin.add_ons.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Powerup Name</label>
                                <input type="text" name="name" required placeholder="e.g. Advanced AI Analytics" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">One-Time Price (₹)</label>
                                <input type="number" name="price" required placeholder="999" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Icon (SVG or Emoji)</label>
                                <input type="text" name="icon" placeholder="🚀" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Short Description</label>
                                <textarea name="description" required rows="4" placeholder="What does this powerup unlock for the institute?" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                            </div>
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 dark:shadow-none flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Launch Powerup
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Powerup Inventory --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-10 border-b border-gray-50 dark:border-gray-700">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Inventory</h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Live Marketplace Items</p>
                        </div>
                        
                        <div class="divide-y divide-gray-50 dark:divide-gray-700">
                            @forelse($addOns as $addOn)
                            <div class="p-10 group hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-all">
                                <div class="flex items-start justify-between gap-8">
                                    <div class="flex items-start gap-6">
                                        <div class="w-16 h-16 rounded-3xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                                            {{ $addOn->icon ?? '📦' }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 mb-1">
                                                <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $addOn->name }}</h4>
                                                @if($addOn->is_promoted)
                                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[8px] font-black uppercase tracking-widest border border-amber-100 animate-pulse">Promoted</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 font-medium max-w-lg mb-4">{{ $addOn->description }}</p>
                                            <div class="flex items-center gap-6">
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">One-Time Price</span>
                                                    <span class="text-sm font-black text-gray-900 dark:text-white uppercase italic">₹{{ number_format($addOn->price) }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Total Sales</span>
                                                    <span class="text-sm font-black text-indigo-600 uppercase italic">{{ $addOn->institutes_count }} Units</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <form action="{{ route('superadmin.add_ons.toggle_promotion', $addOn) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $addOn->is_promoted ? 'bg-amber-500 text-white shadow-lg' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                                {{ $addOn->is_promoted ? 'Stop Promo' : 'Promote on Dashboard' }}
                                            </button>
                                        </form>
                                        <button class="px-4 py-2 bg-gray-50 text-gray-400 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-rose-50 hover:text-rose-600 transition-all">
                                            Archive Powerup
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-20 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Marketplace is empty</h4>
                                <p class="text-xs text-gray-500 mt-2">Start by forging your first powerup using the panel on the left.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
