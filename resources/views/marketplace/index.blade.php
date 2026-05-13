<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                    Powerup<br>Marketplace
                </h2>
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mt-1">Plug-n-Play Elite Features</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Inventory</p>
                    <p class="text-xs font-black text-gray-900 dark:text-white uppercase italic">{{ count($purchasedIds) }} Powerups Active</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-8 sm:px-10 lg:px-12">
            
            {{-- Category Filter or Header --}}
            <div class="mb-12">
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Available Upgrades</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">One-time payment. Lifetime access.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($addOns as $addOn)
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border {{ in_array($addOn->id, $purchasedIds) ? 'border-indigo-500' : 'border-gray-100 dark:border-gray-700' }} p-8 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
                    
                    @if(in_array($addOn->id, $purchasedIds))
                        <div class="absolute top-0 right-0 bg-indigo-600 text-white px-4 py-1 text-[8px] font-black uppercase tracking-widest rounded-bl-2xl">Owned</div>
                    @endif

                    <div class="w-16 h-16 rounded-3xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition-transform">
                        {{ $addOn->icon ?? '📦' }}
                    </div>

                    <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight mb-2">{{ $addOn->name }}</h4>
                    <p class="text-xs text-gray-500 font-medium mb-8 min-h-[3rem] line-clamp-3">{{ $addOn->description }}</p>

                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-50 dark:border-gray-700">
                        <div>
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">One-Time Fee</p>
                            <p class="text-xl font-black text-gray-900 dark:text-white uppercase italic">₹{{ number_format($addOn->price) }}</p>
                        </div>
                        
                        @if(in_array($addOn->id, $purchasedIds))
                            <button disabled class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest cursor-not-allowed">
                                Activated
                            </button>
                        @else
                            <form action="{{ route('marketplace.purchase', $addOn) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-gray-200 dark:shadow-none">
                                    Unlock Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-400 font-black uppercase tracking-widest italic">New Powerups coming soon...</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
