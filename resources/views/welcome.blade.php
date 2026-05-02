<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CoachPro | The Ultimate Coaching Management System</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .hero-mesh {
                background-color: #0f172a;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(253,16%,15%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, hsla(225,39%,25%,1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(339,49%,25%,1) 0, transparent 50%);
            }
            .glass-nav {
                background: rgba(15, 23, 42, 0.8);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            .text-gradient {
                background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        </style>
    </head>
    <body class="antialiased font-sans bg-[#0f172a] text-gray-100 selection:bg-indigo-500 selection:text-white overflow-x-hidden">
        
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 glass-nav">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-application-logo class="h-10 w-auto" />
                </div>
                
                <div class="hidden md:flex items-center gap-8 text-sm font-bold tracking-tight uppercase">
                    <a href="#features" class="hover:text-indigo-400 transition-colors">Features</a>
                    <a href="#pricing" class="hover:text-indigo-400 transition-colors">Pricing</a>
                    <a href="#testimonials" class="hover:text-indigo-400 transition-colors">Success Stories</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-gradient-indigo">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold uppercase hover:text-indigo-400 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-gradient-indigo">Start Free Trial</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-mesh">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative z-10 space-y-8 animate-float">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-black bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase tracking-widest">
                        🚀 Version 2.0 is Live
                    </span>
                    <h1 class="text-6xl lg:text-8xl font-black leading-[0.9] tracking-tighter">
                        Manage Your <span class="text-gradient">Coaching</span> Like a Pro.
                    </h1>
                    <p class="text-lg text-gray-400 max-w-lg leading-relaxed">
                        The all-in-one platform to track students, attendance, fees, and performance. Spend less time on paperwork and more time teaching.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('register') }}" class="btn-gradient-indigo text-base px-10 py-5">
                            Get Started for Free
                        </a>
                        <a href="#features" class="px-10 py-5 rounded-2xl bg-white/5 border border-white/10 text-base font-black hover:bg-white/10 transition-all flex items-center gap-2">
                            Explore Features
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                        </a>
                    </div>
                    <div class="flex items-center gap-6 pt-8 border-t border-white/5">
                        <div>
                            <p class="text-2xl font-black">500+</p>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Institutes</p>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div>
                            <p class="text-2xl font-black">50k+</p>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">Students</p>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div>
                            <p class="text-2xl font-black">4.9/5</p>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">User Rating</p>
                        </div>
                    </div>
                </div>

                <div class="relative lg:h-[600px] flex items-center justify-center">
                    <div class="absolute inset-0 bg-indigo-500/20 blur-[100px] rounded-full"></div>
                    <img src="{{ asset('hero-mockup.png') }}" alt="CoachPro Dashboard" class="relative z-10 w-full h-auto rounded-[2.5rem] shadow-2xl border border-white/10 animate-float" style="animation-delay: -3s;">
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="py-32 bg-[#0a0f1d]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20 space-y-4">
                    <h2 class="text-4xl lg:text-6xl font-black tracking-tighter">Everything you need to <span class="text-gradient">Scale.</span></h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Focus on teaching, we'll handle the rest. Powerful tools built for modern educators.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $features = [
                            ['title' => 'Student Management', 'desc' => 'Centralized database for all student profiles, batches, and personal details.', 'icon' => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m9-10a4 4 0 100-8 4 4 0 000 8zm8-7a4 4 0 00-3 3.5M16 11a4 4 0 013 3.5m0 0V19m0-8a4 4 0 013 3.5', 'color' => 'indigo'],
                            ['title' => 'Academic Library', 'desc' => 'Digital vault for Study Materials. Upload PDFs, share video lectures, and links.', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'violet'],
                            ['title' => 'Branded Portals', 'desc' => 'Get a professional URL (e.g., ica.coachpro.com) to provide a premium experience.', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'color' => 'emerald'],
                            ['title' => 'Attendance Tracking', 'desc' => 'Smart attendance system with real-time notifications for parents.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 16h.01', 'color' => 'rose'],
                            ['title' => 'Audit Activity Logs', 'desc' => 'Complete accountability. Track every addition, update, and deletion across your institute.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'amber'],
                            ['title' => 'Fee Records', 'desc' => 'Track pending fees. Generate automated PDF receipts and share via WhatsApp.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'blue'],
                            ['title' => 'Online Quizzes', 'desc' => 'Create and conduct online tests. Automated grading and detailed result analytics.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'color' => 'indigo'],
                            ['title' => 'Mobile First', 'desc' => 'Completely responsive and installable as a PWA. Manage your institute on the go.', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'color' => 'violet'],
                            ['title' => 'Lead Tracking', 'desc' => 'Don\'t lose a single inquiry. Manage follow-ups and convert leads efficiently.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'rose'],
                        ];
                    @endphp
 
                    @foreach($features as $f)
                        <div class="p-8 rounded-[2rem] bg-white/5 border border-white/5 hover:border-{{ $f['color'] }}-500/50 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-{{ $f['color'] }}-500/10 rounded-full blur-2xl group-hover:bg-{{ $f['color'] }}-500/20 transition-all"></div>
                            
                            <div class="w-12 h-12 rounded-xl bg-{{ $f['color'] }}-500/10 text-{{ $f['color'] }}-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $f['icon'] }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            </div>
                            
                            <h3 class="text-lg font-black mb-3 tracking-tight group-hover:text-{{ $f['color'] }}-400 transition-colors">{{ $f['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed font-medium">{{ $f['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-32 bg-[#0f172a]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20 space-y-4">
                    <h2 class="text-4xl lg:text-6xl font-black tracking-tighter">Simple, <span class="text-gradient">Transparent</span> Pricing.</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Choose a plan that scales with your growth. No hidden fees, no credit card required.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Starter --}}
                    <div class="p-10 rounded-[3rem] bg-white/5 border border-white/10 hover:border-indigo-500/50 transition-all flex flex-col">
                        <h3 class="text-2xl font-black mb-2">Monthly</h3>
                        <p class="text-gray-500 text-sm mb-8">Perfect for small coaching centers starting out.</p>
                        <div class="mb-8">
                            <span class="text-5xl font-black">₹{{ \App\Models\Setting::get('monthly_price', 999) }}</span>
                            <span class="text-gray-500 text-sm">/ month</span>
                        </div>
                        <ul class="space-y-4 mb-10 flex-1">
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Unlimited Students & Batches
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                All Core Modules Included
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Basic Analytics
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="w-full py-4 rounded-2xl bg-white/5 border border-white/10 text-center font-black hover:bg-white/10 transition-all">Start Trial</a>
                    </div>

                    {{-- Pro --}}
                    <div class="p-10 rounded-[3rem] bg-indigo-600 relative overflow-hidden flex flex-col shadow-2xl shadow-indigo-500/20 scale-110 z-10">
                        <div class="absolute top-6 right-6 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Most Popular</div>
                        <h3 class="text-2xl font-black mb-2 text-white">6-Month Plan</h3>
                        <p class="text-indigo-100 text-sm mb-8">Advanced features for growing institutes.</p>
                        <div class="mb-8">
                            <span class="text-5xl font-black text-white">₹{{ \App\Models\Setting::get('six_month_price', 4999) }}</span>
                            <span class="text-indigo-200 text-sm">/ 6 months</span>
                        </div>
                        <ul class="space-y-4 mb-10 flex-1">
                            <li class="flex items-center gap-3 text-sm text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                <strong>Significant Savings</strong> Included
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Branded Subdomains
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Priority Support
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="w-full py-4 rounded-2xl bg-white text-indigo-600 text-center font-black hover:scale-105 transition-all shadow-lg shadow-indigo-900/20">Get Started</a>
                    </div>

                    {{-- Enterprise --}}
                    <div class="p-10 rounded-[3rem] bg-white/5 border border-white/10 hover:border-violet-500/50 transition-all flex flex-col">
                        <h3 class="text-2xl font-black mb-2">Custom</h3>
                        <p class="text-gray-500 text-sm mb-8">Flexible plans for large-scale operations.</p>
                        <div class="mb-8">
                            <span class="text-4xl font-black">Flexible</span>
                            <span class="text-gray-500 text-sm"> Pricing</span>
                        </div>
                        <ul class="space-y-4 mb-10 flex-1">
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Select Your Months
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                <strong>Up to {{ \App\Models\Setting::get('bulk_discount_percentage', 30) }}% Discount</strong>
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Dedicated Account Manager
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="w-full py-4 rounded-2xl bg-white/5 border border-white/10 text-center font-black hover:bg-white/10 transition-all">Contact Sales</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20">
            <div class="max-w-5xl mx-auto px-6">
                <div class="bg-gradient-to-br from-indigo-600 to-violet-800 rounded-[3rem] p-12 text-center space-y-8 shadow-2xl shadow-indigo-500/20">
                    <h2 class="text-4xl lg:text-6xl font-black tracking-tighter">Ready to transform your institute?</h2>
                    <p class="text-indigo-100 text-lg max-w-xl mx-auto">Join hundreds of successful educators who are growing their coaching business with CoachPro.</p>
                    <div class="pt-4">
                        <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-12 py-5 rounded-2xl text-lg font-black hover:scale-105 active:scale-95 transition-all inline-block uppercase tracking-widest shadow-xl">
                            Start 14-Day Free Trial
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <x-application-logo class="h-8 w-auto grayscale" />
                </div>
                <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">© {{ date('Y') }} CoachPro SaaS. All rights reserved.</p>
                <div class="flex gap-8 text-xs font-bold uppercase tracking-widest text-gray-500">
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
            </div>
        </footer>

    </body>
</html>
