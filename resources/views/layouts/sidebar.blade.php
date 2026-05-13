<div class="hidden sm:flex flex-col w-72 h-screen fixed left-0 top-0 p-4 z-40">
    <div class="flex flex-col h-full bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-quonix-purple/10 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center px-8 h-28 shrink-0">
            <a href="{{ auth()->user()->role === 'superadmin' ? route('superadmin.index') : route('dashboard') }}" class="group">
                <x-application-logo class="h-16 w-auto transition-transform duration-500 group-hover:rotate-3 group-hover:scale-110" />
            </a>
            <div class="ml-auto">
                <span class="text-[10px] font-black text-quonix-purple bg-quonix-purple/10 dark:bg-quonix-purple/30 px-2 py-0.5 rounded-full uppercase tracking-widest">v{{ config('app.version') }}</span>
            </div>
        </div>

        <div id="sidebar-scroll" class="flex flex-col flex-1 overflow-y-auto custom-scrollbar px-4 pb-4">
            <nav class="space-y-1">
                <div class="px-4 pb-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Main Menu</span>
                </div>
                
                {{-- Global/Superadmin Dashboard Link --}}
                <x-sidebar-link :href="auth()->user()->role === 'superadmin' ? route('superadmin.index') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('superadmin.index')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    {{ __('Insights') }}
                </x-sidebar-link>

                {{-- SUPERADMIN-ONLY LINKS --}}
                @if(auth()->user()->role === 'superadmin')
                    <div class="pt-8 pb-2 px-4">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Platform</span>
                    </div>
                    <x-sidebar-link :href="route('superadmin.index')" :active="request()->routeIs('superadmin.index')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        {{ __('Institutes') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('superadmin.add_ons.index')" :active="request()->routeIs('superadmin.add_ons.*')" icon="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                        {{ __('Powerup Market') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('superadmin.contact-leads.index')" :active="request()->routeIs('superadmin.contact-leads.*')" icon="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        {{ __('Contact Leads') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('superadmin.settings')" :active="request()->routeIs('superadmin.settings')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0">
                        {{ __('Platform Settings') }}
                    </x-sidebar-link>
                @endif

                {{-- Institute-Specific Links: ONLY show if we are in an institute context --}}
                @if(isset($resolved_institute))
                    <x-sidebar-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')" icon="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-2.394 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946 2.394 3.42 3.42 0 010 4.606 3.42 3.42 0 00-1.946 2.394 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-2.394 3.42 3.42 0 010-4.606z">
                        {{ __('Leaderboard') }}
                    </x-sidebar-link>

                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-4 py-3.5 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('notifications.*') ? 'bg-quonix-purple text-white shadow-xl shadow-quonix-purple/20' : 'text-gray-500 dark:text-gray-400 hover:bg-quonix-purple/5 dark:hover:bg-gray-700/50 hover:text-quonix-purple' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            <span>{{ __('Inbox') }}</span>
                        </div>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-black bg-rose-500 text-white rounded-full animate-pulse">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher' || auth()->user()->role === 'receptionist')
                        <div class="pt-8 pb-2 px-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Academic</span>
                        </div>
                        @if(auth()->user()->role !== 'receptionist')
                            <x-sidebar-link :href="route('batches.index')" :active="request()->routeIs('batches.*')" icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                {{ __('Class Batches') }}
                            </x-sidebar-link>
                        @endif
                        <x-sidebar-link :href="route('students.index')" :active="request()->routeIs('students.*')" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            {{ __('Student CRM') }}
                        </x-sidebar-link>
                        @if(auth()->user()->role !== 'receptionist')
                            <x-sidebar-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                {{ __('Attendance') }}
                            </x-sidebar-link>
                            <x-sidebar-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                {{ __('Assessment') }}
                            </x-sidebar-link>
                            <x-sidebar-link :href="route('study_materials.index')" :active="request()->routeIs('study_materials.*')" icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                {{ __('Study Materials') }}
                            </x-sidebar-link>
                        @endif
                    @endif

                    @if(auth()->user()->role === 'student')
                        <div class="pt-8 pb-2 px-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Learning</span>
                        </div>
                        <x-sidebar-link :href="route('student.quizzes.index')" :active="request()->routeIs('student.quizzes.*')" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            {{ __('Assessments') }}
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('study_materials.index')" :active="request()->routeIs('study_materials.*')" icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            {{ __('Materials') }}
                        </x-sidebar-link>
                    @endif

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'accountant' || auth()->user()->role === 'receptionist')
                        <div class="pt-8 pb-2 px-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Management</span>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <x-sidebar-link :href="route('staff.index')" :active="request()->routeIs('staff.*')" icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0">
                                {{ __('Staff Management') }}
                            </x-sidebar-link>
                        @endif
                        @if(auth()->user()->role !== 'accountant')
                            <x-sidebar-link :href="route('enquiries.index')" :active="request()->routeIs('enquiries.*')" icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                {{ __('Leads & Inquiry') }}
                            </x-sidebar-link>
                        @endif
                        @if(auth()->user()->role !== 'receptionist')
                            <x-sidebar-link :href="route('fees.index')" :active="request()->routeIs('fees.*')" icon="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                {{ __('Finance Hub') }}
                            </x-sidebar-link>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <div class="pt-8 pb-2 px-4">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Administration</span>
                            </div>
                            <x-sidebar-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                {{ __('Assessments') }}
                            </x-sidebar-link>
                            <x-sidebar-link :href="route('subscription.index')" :active="request()->routeIs('subscription.*')" icon="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                {{ __('Bills & Plans') }}
                            </x-sidebar-link>
                            <x-sidebar-link :href="route('institute.settings')" :active="request()->routeIs('institute.settings')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0">
                                {{ __('Settings') }}
                            </x-sidebar-link>
                        @endif
                    @endif
                @endif
            </nav>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar-scroll');
                const scrollPos = localStorage.getItem('sidebarScrollPos');
                if (scrollPos) {
                    sidebar.scrollTop = scrollPos;
                }
                sidebar.addEventListener('scroll', function() {
                    localStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
                });
            });
        </script>

        <div class="px-4 mb-4">
            <button onclick="window.installPWA()" class="pwa-install-btn w-full items-center gap-3 px-4 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-2xl text-sm font-black shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all no-loader" style="display: none;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                Install Mobile App
            </button>
        </div>

        <div class="p-6 shrink-0 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-quonix-purple to-quonix-magenta flex items-center justify-center text-white font-black shadow-lg shadow-quonix-purple/20">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-quonix-purple font-black uppercase tracking-widest">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-2 py-2.5 bg-white dark:bg-gray-800 rounded-xl text-xs font-bold text-gray-600 border border-gray-100 dark:border-gray-700 hover:bg-quonix-purple/5 transition-all">
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-white dark:bg-gray-800 rounded-xl text-xs font-bold text-rose-600 border border-gray-100 dark:border-gray-700 hover:bg-rose-50 transition-all">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
