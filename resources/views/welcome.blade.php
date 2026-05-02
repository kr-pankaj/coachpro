<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Coaching Management System</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 selection:bg-indigo-500 selection:text-white">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                <nav class="absolute top-0 right-0 p-6 flex justify-end w-full z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif

            <div class="max-w-4xl mx-auto p-6 lg:p-8 text-center z-10 relative">
                <div class="flex justify-center mb-8">
                    <!-- Simple Logo Icon -->
                    <svg class="w-20 h-20 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                
                <h1 class="text-5xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl mb-6">
                    Coaching Management System
                </h1>
                
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    A simple, powerful, mobile-first platform to manage your tuition classes, students, track attendance, and record fee payments efficiently.
                </p>

                <div class="mt-10 flex items-center justify-center gap-x-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">Get Started</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">Create an account <span aria-hidden="true">→</span></a>
                    @endauth
                </div>
            </div>
            
            <!-- Decorative background elements -->
            <div class="absolute inset-0 -z-10 overflow-hidden">
                <div class="absolute left-[max(50%,25rem)] top-0 -z-10 -translate-x-1/2 blur-3xl xl:-top-6" aria-hidden="true">
                    <div class="aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 dark:opacity-20" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </div>
        </div>
    </body>
</html>
