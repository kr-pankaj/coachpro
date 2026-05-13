<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuonixAI | @yield('title', 'Coaching Management System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --pink: #ec4899; --amber: #f59e0b; }
        body { font-family: 'Inter', sans-serif; background: #ffffff; color: #111827; }
        .brand-gradient { background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%); }
        .text-brand { background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .btn-brand { background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%); color: white; font-weight: 800; border-radius: 14px; transition: all .2s; box-shadow: 0 4px 20px rgba(236,72,153,.3); }
        .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(236,72,153,.4); }
        .nav-glass { background: rgba(255,255,255,.85); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(0,0,0,.06); }
        .hero-bg { background: linear-gradient(160deg, #fff9f0 0%, #fff0f6 50%, #fdf4ff 100%); }
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-gray-50">

{{-- NAV --}}
<nav class="fixed top-0 left-0 right-0 z-50 nav-glass">
    <div class="max-w-7xl mx-auto px-6 h-18 flex items-center justify-between py-4">
        <a href="/">
            <x-application-logo class="h-8 w-auto" />
        </a>
        <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-500">
            <a href="/#features" class="hover:text-pink-600 transition-colors">Features</a>
            <a href="/#pricing" class="hover:text-pink-600 transition-colors">Pricing</a>
            <a href="/#contact" class="hover:text-pink-600 transition-colors">Contact</a>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ auth()->user()->role === 'superadmin' ? route('superadmin.index') : (auth()->user()->institute ? route('dashboard', ['slug' => auth()->user()->institute->slug]) : '#') }}" class="btn-brand px-5 py-2.5 text-sm">Dashboard →</a>
            @else
                <a href="{{ route('login.global') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">Sign in</a>
                <a href="{{ route('register') }}" class="btn-brand px-5 py-2.5 text-sm">Start Free Trial</a>
            @endauth
        </div>
    </div>
</nav>

<main class="pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-6">
        @yield('content')
    </div>
</main>

{{-- FOOTER --}}
<footer class="py-10 border-t border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
        <a href="/">
            <x-application-logo class="h-7 w-auto opacity-70" />
        </a>
        <p class="text-gray-400 text-sm">© {{ date('Y') }} QuonixAI. All rights reserved.</p>
        <div class="flex gap-6 text-sm font-semibold text-gray-400">
            <a href="{{ route('privacy') }}" target="_blank" class="hover:text-pink-500 transition-colors">Privacy</a>
            <a href="{{ route('terms') }}" target="_blank" class="hover:text-pink-500 transition-colors">Terms</a>
            <a href="/#contact" class="hover:text-pink-500 transition-colors">Contact</a>
        </div>
    </div>
</footer>

</body>
</html>
