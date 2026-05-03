<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $institute = request()->get('resolved_institute') ?? (auth()->check() ? auth()->user()->institute : null);
            $pageTitle = $institute ? $institute->name . ' | Portal' : config('app.name', 'QuonixAI');
            $favicon = ($institute && $institute->logo_url) ? $institute->logo_url : asset('favicon.png');
        @endphp

        <title>{{ $pageTitle }}</title>
        <link rel="icon" type="image/png" href="{{ $favicon }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- PWA Meta Tags -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <link rel="apple-touch-icon" href="/icon-192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <!-- Tom Select -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

        <!-- ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
        <style>
            body { font-family: 'Outfit', sans-serif !important; }
            .mesh-bg {
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                z-index: -1;
                background-color: #ffffff;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.03) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, rgba(139, 92, 246, 0.03) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.03) 0, transparent 50%);
            }
            .dark .mesh-bg {
                background-color: #030712;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, rgba(139, 92, 246, 0.1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.1) 0, transparent 50%);
            }
        </style>
    </head>
    <body class="antialiased text-gray-900 selection:bg-indigo-500 selection:text-white">
        <div class="mesh-bg"></div>
        <div class="min-h-screen flex flex-col sm:flex-row relative overflow-x-hidden">

            {{-- Desktop Sidebar --}}
            @auth
                @include('layouts.sidebar')
            @endauth

            <div class="flex-1 flex flex-col sm:ml-72 transition-all duration-300">
                @if(session()->has('impersonated_by'))
                    <div class="sticky top-0 z-[60] bg-gray-900 text-white px-8 py-3 flex items-center justify-between shadow-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-rose-500 rounded-full animate-ping"></div>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Impersonation Mode: <span class="text-indigo-400">{{ auth()->user()->name }}</span> ({{ auth()->user()->institute?->name }})</span>
                        </div>
                        <a href="{{ route('superadmin.stop_impersonate') }}" class="px-4 py-1.5 bg-white text-gray-900 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-lg">
                            Return to Command Center
                        </a>
                    </div>
                @endif
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
                <header class="sticky top-0 z-30 bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl border-b border-gray-100 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto py-8 px-8 sm:px-10 lg:px-12">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-16 sm:pb-0 page-enter">
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
                                $links[] = ['Billing', 'subscription.*', 'subscription.index', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'];
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
        {{-- Global Loader Overlay --}}
        <div id="global-loader" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; background:rgba(255,255,255,0.85); backdrop-filter:blur(8px); transition:opacity 0.3s ease;">
            <div style="display:flex; flex-direction:column; align-items:center;">
                <div style="position:relative; width:4rem; height:4rem;">
                    <div style="position:absolute; inset:0; border:4px solid rgba(79, 70, 229, 0.1); border-radius:50%;"></div>
                    <div style="position:absolute; inset:0; border:4px solid #4f46e5; border-top-color:transparent; border-radius:50%; animation: spin 0.8s linear infinite;"></div>
                </div>
                <p style="margin-top:1.5rem; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:0.3em; color:#4f46e5; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">QuonixAI Thinking...</p>
            </div>
        </div>

        <style>
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
            .loader-active { overflow: hidden !important; pointer-events: none !important; }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loader = document.getElementById('global-loader');
                const loaderText = loader.querySelector('p');
                
                const messages = [
                    'QuonixAI Thinking...',
                    'Optimizing Student Success...',
                    'Organizing Your Academy...',
                    'Finalizing Records...',
                    'Securing Data Integrity...',
                    'Empowering Your Tutors...',
                    'Synchronizing Knowledge...',
                    'Preparing Excellence...'
                ];

                function showLoader() {
                    // Pick a random message
                    loaderText.innerText = messages[Math.floor(Math.random() * messages.length)];
                    
                    loader.style.display = 'flex';
                    loader.style.opacity = '0';
                    document.body.classList.add('loader-active');
                    setTimeout(() => { loader.style.opacity = '1'; }, 10);
                }

                // 1. Intercept all Form Submissions
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        if (form.getAttribute('data-submitting')) return;
                        form.setAttribute('data-submitting', 'true');
                        showLoader();
                        
                        // Disable buttons
                        form.querySelectorAll('button, input[type="submit"]').forEach(btn => {
                            btn.disabled = true;
                            if (btn.classList.contains('btn-gradient-indigo')) btn.innerText = 'Processing...';
                        });
                    });
                });

                // 2. Intercept all Link Clicks (Navigation)
                document.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return;

                    // Skip if: new tab, same page hash, non-http, or download
                    if (link.target === '_blank' || 
                        link.href.includes('#') || 
                        !link.href.startsWith('http') || 
                        link.hasAttribute('download') ||
                        e.ctrlKey || e.metaKey || e.shiftKey) {
                        return;
                    }

                    // Show loader for actual navigation
                    showLoader();
                });

                // 3. Page Unload Guard
                window.addEventListener('beforeunload', function() {
                    showLoader();
                });
            });

            // Reset on Back Button
            window.addEventListener('pageshow', function(event) {
                const loader = document.getElementById('global-loader');
                if (event.persisted) {
                    loader.style.display = 'none';
                    document.body.classList.remove('loader-active');
                    document.querySelectorAll('form').forEach(f => f.removeAttribute('data-submitting'));
                    document.querySelectorAll('button').forEach(b => b.disabled = false);
                }
            });
        </script>
    </body>
</html>
