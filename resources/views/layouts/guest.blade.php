<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $institute = request()->get('resolved_institute') ?? (auth()->check() ? auth()->user()->institute : null);
            $finalTitle = $institute ? $institute->name . ' | Portal' : config('app.name', 'QuonixAI');
            $favicon = ($institute && $institute->logo_url) ? $institute->logo_url : asset('favicon.png') . '?v=2';
        @endphp
        <title>{{ $finalTitle }} — {{ $pageTitle ?? 'Welcome' }}</title>
        <link rel="icon" type="image/png" href="{{ $favicon }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
        
        <!-- PWA Meta Tags -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <link rel="apple-touch-icon" href="/icon-192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

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
            body { font-family: 'Inter', sans-serif; }
            .auth-gradient {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #9333ea 100%);
            }
            .glass-card {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(20px);
            }
            .input-field {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1.5px solid #e5e7eb;
                border-radius: 0.625rem;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                outline: none;
                color: #111827;
                background: #fafafa;
            }
            .input-field:focus {
                border-color: #4f46e5;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
            }
            .input-field::placeholder { color: #9ca3af; }
            .btn-primary {
                width: 100%;
                padding: 0.75rem 1.5rem;
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: white;
                font-weight: 600;
                font-size: 0.9rem;
                border-radius: 0.625rem;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                letter-spacing: 0.01em;
            }
            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 25px rgba(79,70,229,0.4);
            }
            .btn-primary:active { transform: translateY(0); }
            .feature-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
                color: rgba(255,255,255,0.9);
                font-size: 0.9rem;
            }
            .feature-icon {
                width: 2rem; height: 2rem;
                background: rgba(255,255,255,0.2);
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .float-anim { animation: float 4s ease-in-out infinite; }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .fade-in-up { animation: fadeInUp 0.5s ease forwards; }
            .input-group { position: relative; }
            .input-icon {
                position: absolute; left: 0.875rem; top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                pointer-events: none;
                transition: color 0.2s;
            }
            .input-group:focus-within .input-icon { color: #4f46e5; }
            .input-group .input-field { padding-left: 2.75rem; }
        </style>
    </head>
    <body class="antialiased" style="background: #f1f5f9;">
        <div class="min-h-screen flex">
            <!-- Left Panel: Branding -->
            <div class="hidden lg:flex lg:w-5/12 auth-gradient flex-col justify-between p-12 relative overflow-hidden">
                <!-- Decorative circles -->
                <div style="position:absolute;top:-80px;right:-80px;width:300px;height:300px;background:rgba(255,255,255,0.06);border-radius:50%;"></div>
                <div style="position:absolute;bottom:-60px;left:-60px;width:250px;height:250px;background:rgba(255,255,255,0.06);border-radius:50%;"></div>
                <div style="position:absolute;top:40%;left:60%;width:150px;height:150px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>

                <div>
                    <div style="margin-bottom:3rem;">
                        <x-application-logo style="height:3.5rem; width:auto;" />
                    </div>

                    <h1 style="color:white;font-size:2.25rem;font-weight:800;line-height:1.2;letter-spacing:-0.03em;margin-bottom:1rem;">
                        Manage your coaching centre effortlessly.
                    </h1>
                    <p style="color:rgba(255,255,255,0.75);font-size:0.95rem;line-height:1.7;margin-bottom:2.5rem;">
                        The all-in-one platform for tutors and coaching institutes across India.
                    </p>

                    <div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span>Student & batch management</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span>Daily attendance tracking</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span>Fee collection & payment tracking</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.828 14.828a4 4 0 015.656 0l4-4a4 4 0 01-5.656-5.656l-1.102 1.101" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span>Student self-registration portal</span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:1rem;">
                    <div style="display:flex;">
                        @foreach(['A','B','C','D'] as $i)
                        <div style="width:2rem;height:2rem;border-radius:50%;background:rgba(255,255,255,0.3);border:2px solid rgba(255,255,255,0.6);margin-left:-0.5rem;display:flex;align-items:center;justify-content:center;font-size:0.65rem;color:white;font-weight:600;">{{ $i }}</div>
                        @endforeach
                    </div>
                    <span style="color:rgba(255,255,255,0.8);font-size:0.8rem;">Trusted by 100+ institutes</span>
                </div>
            </div>

            <!-- Right Panel: Auth Form -->
            <div class="flex-1 flex flex-col justify-center items-center p-6 lg:p-16" style="background:#f8fafc;">
                <!-- Mobile logo -->
                <div class="lg:hidden mb-8">
                    <x-application-logo class="h-10 w-auto" />
                </div>

                <div class="w-full max-w-md fade-in-up">
                    {{ $slot }}
                </div>
            </div>
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
                    'QuonixAI is thinking...',
                    'Assembling your cockpit...',
                    'Waking up the cloud hamsters...',
                    'Bribing the firewall with cookies...',
                    'Polishing the welcome mat...',
                    'Calculating your future success...',
                    'Counting the bits... one by one...',
                    'Preparing to blow your mind...'
                ];
                
                function showLoader() {
                    loaderText.innerText = messages[Math.floor(Math.random() * messages.length)];
                    loader.style.display = 'flex';
                    loader.style.opacity = '0';
                    document.body.classList.add('loader-active');
                    setTimeout(() => { loader.style.opacity = '1'; }, 10);
                }

                // 1. Unified Form Submission (Delegated)
                document.addEventListener('submit', function(e) {
                    const form = e.target;
                    if (e.defaultPrevented) return;

                    if (form.getAttribute('data-submitting')) {
                        e.preventDefault();
                        return;
                    }

                    form.setAttribute('data-submitting', 'true');
                    showLoader();
                    
                    form.querySelectorAll('button, input[type="submit"]').forEach(btn => {
                        btn.disabled = true;
                        if (btn.classList.contains('btn-primary')) btn.innerText = 'Processing...';
                    });
                });

                // 2. Unified Link Click (Delegated)
                document.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return;

                    if (e.defaultPrevented) return;

                    if (link.target === '_blank' || 
                        link.href.includes('#') || 
                        !link.href.startsWith('http') || 
                        link.hasAttribute('download') ||
                        link.classList.contains('no-loader') ||
                        e.ctrlKey || e.metaKey || e.shiftKey) {
                        return;
                    }

                    showLoader();
                });
            });

            // Reset on Back Button / bfcache
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
