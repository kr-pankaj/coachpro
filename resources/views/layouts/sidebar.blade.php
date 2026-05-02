<div class="hidden sm:flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 fixed h-full z-40 transition-all duration-300">
    <div class="flex items-center px-6 h-20">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="p-2 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none group-hover:rotate-12 transition-transform duration-300">
                <x-application-logo class="w-7 h-7 fill-current text-white" />
            </div>
            <span class="text-xl font-black tracking-tight text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </a>
    </div>

    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-4 py-4 space-y-1">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                {{ __('Dashboard') }}
            </x-sidebar-link>

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                <x-sidebar-link :href="route('batches.index')" :active="request()->routeIs('batches.*')" icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    {{ __('Batches') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('students.index')" :active="request()->routeIs('students.*')" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    {{ __('Students') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    {{ __('Attendance') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    {{ __('Quizzes') }}
                </x-sidebar-link>
            @endif

            @if(auth()->user()->role === 'admin')
                <div class="pt-6 pb-2">
                    <span class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Management</span>
                </div>
                <x-sidebar-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0">
                    {{ __('Teachers') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('enquiries.index')" :active="request()->routeIs('enquiries.*')" icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    {{ __('Leads') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('fees.index')" :active="request()->routeIs('fees.*')" icon="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                    {{ __('Fees') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('profile_requests.index')" :active="request()->routeIs('profile_requests.*')" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    {{ __('Requests') }}
                </x-sidebar-link>
                <x-sidebar-link :href="route('institute.settings')" :active="request()->routeIs('institute.settings')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0">
                    {{ __('Settings') }}
                </x-sidebar-link>
            @endif
        </nav>
    </div>

    <div class="p-4 m-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3 px-2 mb-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">{{ auth()->user()->role }}</p>
            </div>
        </div>
        <div class="space-y-1">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                {{ __('Profile') }}
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>
