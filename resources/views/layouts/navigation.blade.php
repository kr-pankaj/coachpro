<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sm:hidden">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo (Left Side) -->
            <div class="flex shrink-0 items-center">
                <a href="{{ auth()->user()->role === 'superadmin' ? route('superadmin.index') : route('dashboard') }}">
                    <x-application-logo class="h-10 w-auto" />
                </a>
            </div>

            <!-- Mobile Action Icons (Right Side) -->
            <div class="flex items-center sm:hidden gap-2">
                <!-- Mobile Notification Icon -->
                @if(isset($resolved_institute))
                <a href="{{ route('notifications.index') }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1.5 right-1.5 flex items-center justify-center min-w-[14px] h-3.5 px-1 text-[8px] font-black bg-red-500 text-white rounded-full border border-white">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                @endif

                <!-- Hamburger Menu Toggle -->
                <button @click="open = ! open" class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="auth()->user()->role === 'superadmin' ? route('superadmin.index') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('superadmin.index')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(isset($resolved_institute))
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                    <x-responsive-nav-link :href="route('batches.index')" :active="request()->routeIs('batches.*')">
                        {{ __('Batches') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        {{ __('Students') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')">
                        {{ __('Attendance') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('study_materials.index')" :active="request()->routeIs('study_materials.*')">
                        {{ __('Materials') }}
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->role === 'student')
                    <x-responsive-nav-link :href="route('student.quizzes.index')" :active="request()->routeIs('student.quizzes.*')">
                        {{ __('My Tests') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('study_materials.index')" :active="request()->routeIs('study_materials.*')">
                        {{ __('Materials') }}
                    </x-responsive-nav-link>
                @endif
                
                @if(auth()->user()->role === 'admin')
                    <x-responsive-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')">
                        {{ __('Teachers') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('enquiries.index')" :active="request()->routeIs('enquiries.*')">
                        {{ __('Leads') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('fees.index')" :active="request()->routeIs('fees.*')">
                        {{ __('Fees') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile_requests.index')" :active="request()->routeIs('profile_requests.*')">
                        {{ __('Profile Requests') }}
                    </x-responsive-nav-link>
                @endif
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
