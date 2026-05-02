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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $features = [
                            ['title' => 'Student Management', 'desc' => 'Centralized database for all student profiles, batches, and personal details.', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                            ['title' => 'Attendance Tracking', 'desc' => 'Smart attendance system with real-time notifications for parents.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                            ['title' => 'Fee Records', 'desc' => 'Track pending and collected fees. Generate automated PDF receipts instantly.', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
                            ['title' => 'Online Quizzes', 'desc' => 'Create and conduct online tests. Automated grading and detailed result analytics.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                            ['title' => 'Mobile First', 'desc' => 'Completely responsive and installable as a PWA. Manage your institute on the go.', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                            ['title' => 'Lead Tracking', 'desc' => 'Don\'t lose a single inquiry. Manage follow-ups and convert leads efficiently.', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0'],
                        ];
                    @endphp

                    @foreach($features as $f)
                        <div class="p-8 rounded-[2.5rem] bg-white/5 border border-white/5 hover:border-indigo-500/50 transition-all hover:-translate-y-2 group">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $f['icon'] }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <h3 class="text-xl font-black mb-3">{{ $f['title'] }}</h3>
                            <p class="text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
                        </div>
                    @endforeach
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
