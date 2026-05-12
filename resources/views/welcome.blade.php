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
            <div class="section-pill">🚀 Now live — QuonixAI v1.0.6</div>
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
            <img src="{{ asset('hero-mockup-v2.png') }}" alt="QuonixAI Dashboard" class="relative z-10 w-full max-w-lg rounded-[2rem] shadow-2xl ring-1 ring-black/5">
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <div class="section-pill mx-auto">✨ Everything you need</div>
            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 tracking-tight">Powerful features, <span class="text-brand">zero complexity.</span></h2>
            <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Built specifically for coaching institutes. Every feature is designed to save you time and grow your business.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $features = [
                ['icon'=>'👨‍🎓','title'=>'Student Management','desc'=>'Full student profiles with batch enrollment, status tracking, and contact info. Searchable and filterable at scale.','color'=>'pink'],
                ['icon'=>'✅','title'=>'Attendance Tracking','desc'=>'Mark attendance in one tap. Parents get instant notifications. View weekly/monthly reports in seconds.','color'=>'amber'],
                ['icon'=>'💰','title'=>'Fee Management','desc'=>'Track pending and paid fees, generate PDF receipts, and send WhatsApp reminders automatically.','color'=>'green'],
                ['icon'=>'📚','title'=>'Academic Library','desc'=>'Upload PDFs, share video links, and organize study materials by batch. Always accessible from mobile.','color'=>'blue'],
                ['icon'=>'📝','title'=>'Online Quizzes','desc'=>'Create MCQ tests with auto-grading and instant results. Students get a beautiful leaderboard view.','color'=>'violet'],
                ['icon'=>'📊','title'=>'AI-Powered Insights','desc'=>'AI detects at-risk students, suggests follow-up messages for leads, and gives smart retention tips.','color'=>'rose'],
                ['icon'=>'🌐','title'=>'Branded Portal','desc'=>'Your own URL: ourdomain.com/yourname. Full branding with your logo and custom colors.','color'=>'indigo'],
                ['icon'=>'📱','title'=>'Mobile First PWA','desc'=>'Works perfectly on any phone. Installable as an app. Designed for on-the-go institute management.','color'=>'cyan'],
                ['icon'=>'🔒','title'=>'Secure & Isolated','desc'=>'Each institute\'s data is fully isolated. Role-based access for admins, teachers, and students.','color'=>'gray'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="feature-card p-7">
                <div class="text-3xl mb-4">{{ $f['icon'] }}</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $f['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING --}}
<section id="pricing" class="py-28" style="background:linear-gradient(160deg,#fff9f0,#fff0f6);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <div class="section-pill mx-auto">💳 Pricing</div>
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
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-pink-600 text-xs font-black uppercase tracking-widest px-5 py-1.5 rounded-full shadow-lg border border-pink-100">
                    🏆 Most Popular
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
            <div class="section-pill mx-auto">📬 Get in Touch</div>
            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 tracking-tight">Talk to <span class="text-brand">our team.</span></h2>
            <p class="mt-4 text-gray-500 text-lg">Have questions or want a custom plan? Fill out the form and we'll get back to you within 24 hours.</p>
        </div>

        @if(session('contact_success'))
            <div class="mb-8 p-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-semibold text-center text-base">
                🎉 {{ session('contact_success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="bg-white border border-gray-100 rounded-3xl shadow-xl p-8 space-y-5">
            @csrf
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
            <button type="submit" class="w-full btn-brand py-4 text-base font-black">
                Send Enquiry — We'll respond within 24 hours
            </button>
        </form>
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
            <a href="#" class="hover:text-pink-500 transition-colors">Privacy</a>
            <a href="#" class="hover:text-pink-500 transition-colors">Terms</a>
            <a href="#contact" class="hover:text-pink-500 transition-colors">Contact</a>
        </div>
    </div>
</footer>

</body>
</html>
