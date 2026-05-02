<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sm:hidden">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo (Left Side) -->
            <div class="flex shrink-0 items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="h-10 w-auto" />
                </a>
            </div>

            <!-- Desktop Navigation (Hidden on Mobile) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-8">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                    <x-nav-link :href="route('batches.index')" :active="request()->routeIs('batches.*')">
                        {{ __('Batches') }}
                    </x-nav-link>
                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        {{ __('Students') }}
                    </x-nav-link>
                    <x-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')">
                        {{ __('Attendance') }}
                    </x-nav-link>
                    <x-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                        {{ __('Quizzes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('study_materials.index')" :active="request()->routeIs('study_materials.*')">
                        {{ __('Materials') }}
                    </x-nav-link>
                @endif
                
                @if(auth()->user()->role === 'student')
                    <x-nav-link :href="route('student.quizzes.index')" :active="request()->routeIs('student.quizzes.*')">
                        {{ __('My Tests') }}
                    </x-nav-link>
                    <x-nav-link :href="route('study_materials.index')" :active="request()->routeIs('study_materials.*')">
                        {{ __('Materials') }}
                    </x-nav-link>
                @endif
                
                @if(auth()->user()->role === 'admin')
                    <x-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')">
                        {{ __('Teachers') }}
                    </x-nav-link>
                    <x-nav-link :href="route('enquiries.index')" :active="request()->routeIs('enquiries.*')">
                        {{ __('Leads') }}
                    </x-nav-link>
                    <x-nav-link :href="route('fees.index')" :active="request()->routeIs('fees.*')">
                        {{ __('Fees') }}
                    </x-nav-link>
                    <x-nav-link :href="route('profile_requests.index')" :active="request()->routeIs('profile_requests.*')">
                        {{ __('Profile Requests') }}
                    </x-nav-link>
                @endif

                <!-- Desktop Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        @if(auth()->user()->role === 'admin')
                        <x-dropdown-link :href="route('institute.settings')">
                            {{ __('Institute Settings') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('subscription.index')">
                            {{ __('Subscription') }}
                        </x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Action Icons (Right Side) -->
            <div class="flex items-center sm:hidden gap-2">
                <!-- Mobile Notification Icon -->
                <a href="{{ route('notifications.index') }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1.5 right-1.5 flex items-center justify-center min-w-[14px] h-3.5 px-1 text-[8px] font-black bg-red-500 text-white rounded-full border border-white">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>

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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
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
