<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CoachPro') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- PWA Meta Tags -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <link rel="apple-touch-icon" href="/icon-192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <!-- Tom Select -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col sm:flex-row">
            {{-- Desktop Sidebar --}}
            @auth
                @include('layouts.sidebar')
            @endauth

            <div class="flex-1 flex flex-col sm:ml-64 transition-all duration-300">
                @include('layouts.navigation')

            {{-- Trial / Subscription Banner --}}
            @auth
                @if(auth()->user()->role === 'admin' && auth()->user()->institute)
                    @php
                        $inst        = auth()->user()->institute;
                        $trialEnds   = $inst->created_at->addDays(14);
                        $isInTrial   = $trialEnds->isFuture();
                        $hasSubsc    = !empty($inst->razorpay_subscription_id);
                        $isFree      = $inst->is_lifetime_free;
                        $daysLeft    = $isInTrial ? (int) ceil(now()->floatDiffInDays($trialEnds)) : 0;
                    @endphp

                    @if(!$isFree && !$hasSubsc && $isInTrial)
                        {{-- Trial active banner --}}
                        <div id="trial-banner" style="background:linear-gradient(90deg,#f59e0b,#f97316);color:white;padding:0.6rem 1rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;font-size:0.85rem;">
                            <div style="display:flex;align-items:center;gap:0.625rem;">
                                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>
                                    <strong>Free Trial:</strong>
                                    @if($daysLeft <= 1)
                                        Your trial expires <strong>today</strong>! Subscribe now to keep your data and access.
                                    @else
                                        <strong>{{ $daysLeft }} day{{ $daysLeft == 1 ? '' : 's' }}</strong> remaining on your free trial (expires {{ $trialEnds->format('M d') }}).
                                    @endif
                                </span>
                            </div>
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <a href="{{ route('subscription.index') }}" style="background:white;color:#c2410c;font-weight:700;padding:0.3rem 0.875rem;border-radius:999px;text-decoration:none;font-size:0.8rem;white-space:nowrap;transition:opacity 0.2s;" onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1">
                                    Subscribe now →
                                </a>
                                <button onclick="document.getElementById('trial-banner').style.display='none'" style="background:none;border:none;color:rgba(255,255,255,0.8);cursor:pointer;font-size:1.1rem;line-height:1;padding:0;" title="Dismiss">✕</button>
                            </div>
                        </div>
                    @endif
                @endif
            @endauth

            {{-- Page Heading --}}
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-16 sm:pb-0">
                {{ $slot }}
            </main>

            {{-- Mobile bottom nav (Admin & Teacher Only) --}}
            @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex sm:hidden z-50">
                        @php 
                            $links = [];
                            if (auth()->user()->role === 'admin') {
                                $links[] = ['Leads', 'enquiries.*', 'enquiries.index', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0'];
                            }
                            $links[] = ['Students', 'students.*', 'students.index', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
                            $links[] = ['Attendance', 'attendances.*', 'attendances.index', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'];
                            
                            if (auth()->user()->role === 'admin') {
                                $links[] = ['Fees', 'fees.*', 'fees.index', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'];
                            }
                        @endphp
                        <a href="{{ route('dashboard') }}" class="flex-1 flex flex-col items-center py-2 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-xs mt-0.5 font-medium">Home</span>
                        </a>
                        @foreach($links as [$label, $routePattern, $routeName, $icon])
                        <a href="{{ route($routeName) }}" class="flex-1 flex flex-col items-center py-2 {{ request()->routeIs($routePattern) ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $icon }}" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-xs mt-0.5 font-medium">{{ $label }}</span>
                        </a>
                        @endforeach
                    </div>
                @endif
            @endauth
            </div>
        </div>
    </body>
</html>
