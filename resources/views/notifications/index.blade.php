<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            <form action="{{ route('notifications.read_all') }}" method="POST">
                @csrf
                <x-primary-button type="submit">
                    {{ __('Mark All as Read') }}
                </x-primary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse ($notifications as $notification)
                        <div class="p-8 flex items-start gap-6 transition-all duration-300 {{ $notification->read_at ? 'opacity-60 bg-white dark:bg-gray-800' : 'bg-indigo-50/20 dark:bg-indigo-900/10' }}">
                            <div class="flex-shrink-0">
                                @php
                                    $icon = 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9';
                                    $colorClass = 'indigo';
                                    if(isset($notification->data['type'])){
                                        if($notification->data['type'] == 'student_registration') { $icon = 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'; $colorClass = 'emerald'; }
                                        if($notification->data['type'] == 'fee_payment') { $icon = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'; $colorClass = 'amber'; }
                                    }
                                @endphp
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center text-{{ $colorClass }}-600 relative">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $icon }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                    @if(!$notification->read_at)
                                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-{{ $colorClass }}-500 rounded-full border-2 border-white dark:border-gray-800 animate-pulse"></span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $notification->data['title'] ?? 'System Update' }}</h3>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                                
                                <div class="mt-4 flex items-center gap-6">
                                    @if(isset($notification->data['link']))
                                        <a href="{{ $notification->data['link'] }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-[0.1em] flex items-center gap-1">
                                            View Details
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">
                                                Archive
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">Clean Slate!</h3>
                            <p class="text-gray-400 text-sm mt-1 font-bold italic">You've cleared all your notifications.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
