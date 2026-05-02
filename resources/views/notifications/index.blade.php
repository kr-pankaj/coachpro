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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($notifications as $notification)
                        <div class="p-6 flex items-start gap-4 {{ $notification->read_at ? 'opacity-60' : 'bg-indigo-50/30 dark:bg-indigo-900/10' }}">
                            <div class="flex-shrink-0 mt-1">
                                @php
                                    $icon = 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9';
                                    if(isset($notification->data['type'])){
                                        if($notification->data['type'] == 'student_registration') $icon = 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z';
                                        if($notification->data['type'] == 'fee_payment') $icon = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                        if($notification->data['type'] == 'new_quiz') $icon = 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4';
                                    }
                                @endphp
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-700 shadow-sm flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $icon }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</h3>
                                    <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                
                                <div class="mt-3 flex items-center gap-3">
                                    @if(isset($notification->data['link']))
                                        <a href="{{ $notification->data['link'] }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                            View Details →
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-gray-400 hover:text-gray-600 underline">
                                                Mark as read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <h3 class="text-gray-900 dark:text-white font-bold">All caught up!</h3>
                            <p class="text-gray-500 text-sm">No new notifications for you.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
