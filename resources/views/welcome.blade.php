<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuonixAI | Coaching Management System</title>
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
        .feature-card { background: #fff; border: 1px solid #f3f4f6; border-radius: 24px; transition: all .3s; }
        .feature-card:hover { border-color: #f9a8d4; box-shadow: 0 12px 40px rgba(236,72,153,.12); transform: translateY(-4px); }
        .hero-bg { background: linear-gradient(160deg, #fff9f0 0%, #fff0f6 50%, #fdf4ff 100%); }
        .pricing-popular { background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
        .float { animation: float 5s ease-in-out infinite; }
        .section-pill { display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,rgba(236,72,153,.08),rgba(245,158,11,.08));border:1px solid rgba(236,72,153,.2);color:#be185d;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;padding:6px 16px;border-radius:999px;margin-bottom:16px; }
    </style>
    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
</head>
<body class="antialiased overflow-x-hidden">

{{-- NAV --}}
<nav class="fixed top-0 left-0 right-0 z-50 nav-glass">
    <div class="max-w-7xl mx-auto px-6 h-18 flex items-center justify-between py-4">
        <x-application-logo class="h-8 w-auto" />
        <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-500">
            <a href="#features" class="hover:text-pink-600 transition-colors">Features</a>
            <a href="#pricing" class="hover:text-pink-600 transition-colors">Pricing</a>
            <a href="#contact" class="hover:text-pink-600 transition-colors">Contact</a>
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

{{-- HERO --}}
<section class="hero-bg pt-32 pb-20 lg:pt-48 lg:pb-32">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-8">
            <div class="section-pill">
                <svg class="w-3.5 h-3.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/></svg>
                Now live — QuonixAI v1.0.6
            </div>
            <h1 class="text-5xl lg:text-7xl font-black leading-tight tracking-tight text-gray-900">
                Run Your Institute<br><span class="text-brand">Like a Pro.</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-lg leading-relaxed">
                The all-in-one coaching management platform. Automate fees, track attendance, engage students — all from a beautiful branded portal.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('register') }}" class="btn-brand inline-flex items-center gap-2 px-8 py-4 text-base">
                    Get started free <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#features" class="inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-gray-700 bg-white border border-gray-200 rounded-2xl hover:border-pink-300 hover:text-pink-600 transition-all">
                    Explore features
                </a>
            </div>
            <div class="flex flex-wrap gap-6 text-sm font-semibold text-gray-400 pt-2">
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> No credit card needed</span>
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> 14-day free trial</span>
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Setup in 2 minutes</span>
            </div>
        </div>
        <div class="relative flex justify-center float">
            <div class="absolute inset-0 brand-gradient opacity-10 blur-[60px] rounded-full scale-90"></div>
            <img src="{{ asset('hero.png') }}" alt="QuonixAI Dashboard" class="relative z-10 w-full max-w-lg rounded-[2rem] shadow-2xl ring-1 ring-black/5">
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <div class="section-pill mx-auto">
                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/></svg>
                Everything you need
            </div>
            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 tracking-tight">Powerful features, <span class="text-brand">zero complexity.</span></h2>
            <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Built specifically for coaching institutes. Every feature is designed to save you time and grow your business.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $features = [
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />',
                    'title' => 'Student Management',
                    'desc' => 'Full student profiles with batch enrollment, status tracking, and contact info. Searchable and filterable at scale.',
                    'color' => 'pink'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                    'title' => 'Attendance Tracking',
                    'desc' => 'Mark attendance in one tap. Parents get instant notifications. View weekly/monthly reports in seconds.',
                    'color' => 'amber'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75-6.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 .75.75v10.5a.75.75 0 0 1-.75.75H3.75a.75.75 0 0 1-.75-.75V7.5Z" />',
                    'title' => 'Fee Management',
                    'desc' => 'Track pending and paid fees, generate PDF receipts, and send WhatsApp reminders automatically.',
                    'color' => 'emerald'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />',
                    'title' => 'Academic Library',
                    'desc' => 'Upload PDFs, share video links, and organize study materials by batch. Always accessible from mobile.',
                    'color' => 'blue'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84a51.38 51.38 0 0 0-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443" />',
                    'title' => 'Online Quizzes',
                    'desc' => 'Create MCQ tests with auto-grading and instant results. Students get a beautiful leaderboard view.',
                    'color' => 'violet'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />',
                    'title' => 'AI-Powered Insights',
                    'desc' => 'AI detects at-risk students, suggests follow-up messages for leads, and gives smart retention tips.',
                    'color' => 'rose'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582" />',
                    'title' => 'Branded Portal',
                    'desc' => 'Your own URL: ourdomain.com/yourname. Full branding with your logo and custom colors.',
                    'color' => 'indigo'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />',
                    'title' => 'Mobile First PWA',
                    'desc' => 'Works perfectly on any phone. Installable as an app. Designed for on-the-go institute management.',
                    'color' => 'cyan'
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />',
                    'title' => 'Secure & Isolated',
                    'desc' => 'Each institute\'s data is fully isolated. Role-based access for admins, teachers, and students.',
                    'color' => 'gray'
                ],
            ];
            @endphp
            @foreach($features as $f)
            <div class="feature-card p-8 group relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-{{ $f['color'] }}-50 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 transform scale-0 group-hover:scale-100"></div>
                <div class="w-14 h-14 bg-{{ $f['color'] }}-100 rounded-2xl flex items-center justify-center mb-6 text-{{ $f['color'] }}-600 group-hover:bg-{{ $f['color'] }}-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7">
                        {!! $f['icon'] !!}
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 tracking-tight">{{ $f['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed relative z-10">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING --}}
<section id="pricing" class="py-28" style="background:linear-gradient(160deg,#fff9f0,#fff0f6);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <div class="section-pill mx-auto">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Pricing
            </div>
            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 tracking-tight">Simple, <span class="text-brand">transparent</span> pricing.</h2>
            <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Start free. Upgrade when you're ready. No hidden fees, ever.</p>
        </div>

        @php
            $monthly  = \App\Models\Setting::get('monthly_price', 999);
            $sixMonth = \App\Models\Setting::get('six_month_price', 4999);
            $discount = \App\Models\Setting::get('bulk_discount_percentage', 20);
            $monthlyFull = round($monthly * 1.4);
            $sixMonthFull = round($monthly * 6);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-5xl mx-auto">

            {{-- Monthly --}}
            <div class="bg-white rounded-3xl border border-gray-200 p-8 flex flex-col shadow-sm">
                <div class="mb-6">
                    <h3 class="text-xl font-black text-gray-900">Monthly</h3>
                    <p class="text-gray-400 text-sm mt-1">Flexible, month-to-month access.</p>
                </div>
                <div class="mb-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-gray-900">₹{{ number_format($monthly) }}</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <p class="text-xs text-gray-400 line-through mt-1">Normally ₹{{ number_format($monthlyFull) }}/mo</p>
                </div>
                <ul class="space-y-3 mb-8 flex-1 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Unlimited Students & Batches</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> All Core Modules</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Attendance & Fee Tracking</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Email Notifications</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> 14-day Free Trial</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-3.5 text-center font-bold text-gray-700 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all">Start Free Trial</a>
            </div>

            {{-- 6-Month POPULAR --}}
            <div class="relative rounded-3xl p-8 flex flex-col shadow-2xl text-white pricing-popular" style="box-shadow:0 20px 60px rgba(236,72,153,.35);">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-pink-600 text-xs font-black uppercase tracking-widest px-5 py-1.5 rounded-full shadow-lg border border-pink-100 flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05l-3.293 3.293a1 1 0 01-1.414 0l-3.293-3.293a1 1 0 01-.285-1.05l1.738-5.42-1.233-.616a1 1 0 01.894-1.79l1.599.8L10 4.323V3a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Most Popular
                </div>
                <div class="mb-6 mt-3">
                    <h3 class="text-xl font-black">6-Month Plan</h3>
                    <p class="text-white/70 text-sm mt-1">Best value for growing institutes.</p>
                </div>
                <div class="mb-2">
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-black">₹{{ number_format($sixMonth) }}</span>
                        <span class="text-white/70 text-sm">/6 months</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-sm text-white/60 line-through">₹{{ number_format($sixMonthFull) }}</span>
                        <span class="bg-white/20 text-white text-xs font-black px-2 py-0.5 rounded-full">
                            Save ₹{{ number_format($sixMonthFull - $sixMonth) }} ({{ round((($sixMonthFull - $sixMonth) / $sixMonthFull) * 100) }}% off)
                        </span>
                    </div>
                    <p class="text-white/60 text-xs mt-1">Just ₹{{ number_format(round($sixMonth / 6)) }}/month effectively</p>
                </div>
                <ul class="space-y-3 mb-8 flex-1 text-sm text-white/90 mt-4">
                    <li class="flex items-center gap-2"><span class="font-black">✓</span> Everything in Monthly</li>
                    <li class="flex items-center gap-2"><span class="font-black">✓</span> Priority Email Support</li>
                    <li class="flex items-center gap-2"><span class="font-black">✓</span> Branded Tenant Portal URL</li>
                    <li class="flex items-center gap-2"><span class="font-black">✓</span> AI Enquiry Follow-Up Suggestions</li>
                    <li class="flex items-center gap-2"><span class="font-black">✓</span> Extended Payment History</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-4 text-center font-black text-pink-600 bg-white rounded-2xl hover:bg-pink-50 transition-all shadow-lg">Get Started Now →</a>
            </div>

            {{-- Custom --}}
            <div class="bg-white rounded-3xl border border-gray-200 p-8 flex flex-col shadow-sm">
                <div class="mb-6">
                    <h3 class="text-xl font-black text-gray-900">Custom Plan</h3>
                    <p class="text-gray-400 text-sm mt-1">Flexible duration for large operations.</p>
                </div>
                <div class="mb-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-gray-900">Flexible</span>
                    </div>
                    <p class="text-xs text-pink-600 font-bold mt-1">Up to {{ $discount }}% bulk discount</p>
                </div>
                <ul class="space-y-3 mb-8 flex-1 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Choose 1–12 months</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Volume-based discount</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Dedicated account manager</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Custom onboarding support</li>
                    <li class="flex items-center gap-2"><span class="text-pink-500 font-bold">✓</span> Priority feature requests</li>
                </ul>
                <a href="#contact" class="block w-full py-3.5 text-center font-bold text-white rounded-2xl transition-all btn-brand">Contact Sales</a>
            </div>

        </div>
    </div>
</section>

{{-- CONTACT FORM --}}
<section id="contact" class="py-28 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="section-pill mx-auto">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Get in Touch
            </div>
            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 tracking-tight">Talk to <span class="text-brand">our team.</span></h2>
            <p class="mt-4 text-gray-500 text-lg">Have questions or want a custom plan? Fill out the form and we'll get back to you within 24 hours.</p>
        </div>

        @if(session('contact_success'))
            <div class="mb-8 p-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-center text-base flex items-center justify-center gap-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                {{ session('contact_success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" id="contactForm" class="bg-white border border-gray-100 rounded-3xl shadow-xl p-8 space-y-5">
            @csrf
            {{-- Honeypot for spam protection --}}
            <div style="display:none !important;" aria-hidden="true">
                <input type="text" name="website_verification" tabindex="-1" autocomplete="off">
            </div>
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Your Name <span class="text-pink-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Rajesh Kumar" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Email Address <span class="text-pink-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@institute.com" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Mumbai" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Institute Name</label>
                    <input type="text" name="institute_name" value="{{ old('institute_name') }}" placeholder="Bright Future Classes" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Interested Plan</label>
                    <select name="plan_interest" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all bg-white">
                        <option value="">— Select a plan —</option>
                        <option value="monthly" {{ old('plan_interest') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="six_month" {{ old('plan_interest') === 'six_month' ? 'selected' : '' }}>6-Month (Best Value)</option>
                        <option value="custom" {{ old('plan_interest') === 'custom' ? 'selected' : '' }}>Custom Plan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Message</label>
                <textarea name="message" rows="4" placeholder="Tell us about your institute, how many students you have, and any specific requirements..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all resize-none">{{ old('message') }}</textarea>
            </div>
            <div class="flex justify-center">
                <button type="submit" id="submitBtn" class="btn-brand px-12 py-3 text-sm font-black flex items-center gap-2">
                    <span id="btnText">Send Enquiry</span>
                    <svg id="loadingSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>

        <script>
            document.getElementById('contactForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const btn = document.getElementById('submitBtn');
                const text = document.getElementById('btnText');
                const spinner = document.getElementById('loadingSpinner');
                
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                text.innerText = 'Sending...';
                spinner.classList.remove('hidden');

                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact_form'}).then(function(token) {
                            document.getElementById('g-recaptcha-response').value = token;
                            form.submit();
                        });
                    });
                } else {
                    form.submit();
                }
            });
        </script>
    </div>
</section>

{{-- CTA --}}
<section class="py-24" style="background:linear-gradient(135deg,#fdf4ff,#fff9f0);">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-3xl lg:text-5xl font-black text-gray-900 tracking-tight mb-6">Ready to transform<br><span class="text-brand">your institute?</span></h2>
        <p class="text-gray-500 text-lg mb-10 max-w-xl mx-auto">Join coaching institutes across India already using QuonixAI to save time, reduce errors, and grow enrolment.</p>
        <a href="{{ route('register') }}" class="btn-brand inline-flex items-center gap-2 px-10 py-5 text-lg font-black">
            Start Your Free 14-Day Trial
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
        <p class="text-gray-400 text-sm mt-4">No credit card required. Cancel anytime.</p>
    </div>
</section>

{{-- FOOTER --}}
<footer class="py-10 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
        <x-application-logo class="h-7 w-auto opacity-70" />
        <p class="text-gray-400 text-sm">© {{ date('Y') }} QuonixAI. All rights reserved.</p>
        <div class="flex gap-6 text-sm font-semibold text-gray-400">
            <a href="{{ route('privacy') }}" target="_blank" class="hover:text-pink-500 transition-colors">Privacy</a>
            <a href="{{ route('terms') }}" target="_blank" class="hover:text-pink-500 transition-colors">Terms</a>
            <a href="#contact" class="hover:text-pink-500 transition-colors">Contact</a>
        </div>
    </div>
</footer>

</body>
</html>
