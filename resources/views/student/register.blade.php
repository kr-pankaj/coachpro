<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join {{ $institute->name }} — Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family:'Inter',sans-serif; background:#f1f5f9; margin:0; }
        .brand-gradient { background: linear-gradient(135deg, {{ $institute->brand_color ?? '#4f46e5' }}, {{ $institute->brand_color ?? '#4f46e5' }}cc); }
        .input-field { width:100%; padding:0.7rem 1rem; border:1.5px solid #e5e7eb; border-radius:0.625rem; font-size:0.875rem; outline:none; transition:all 0.2s; background:#fafafa; color:#111827; }
        .input-field:focus { border-color:{{ $institute->brand_color ?? '#4f46e5' }}; background:#fff; box-shadow:0 0 0 3px {{ $institute->brand_color ?? '#4f46e5' }}22; }
        .btn-brand { width:100%; padding:0.8rem; font-weight:700; font-size:0.9rem; color:white; border:none; border-radius:0.75rem; cursor:pointer; transition:all 0.2s; background:linear-gradient(135deg, {{ $institute->brand_color ?? '#4f46e5' }}, {{ $institute->brand_color ?? '#4f46e5' }}bb); }
        .btn-brand:hover { opacity:0.9; transform:translateY(-1px); }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- Left: Institute Info --}}
    <div class="brand-gradient lg:w-5/12 flex flex-col justify-between p-8 lg:p-12" style="min-height:220px;">
        <div>
            <div class="flex items-center gap-3 mb-6">
                @if($institute->logo_url)
                    <img src="{{ $institute->logo_url }}" alt="{{ $institute->name }}" class="w-14 h-14 rounded-xl object-contain bg-white p-1">
                @else
                    <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($institute->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-white font-bold text-xl leading-tight">{{ $institute->name }}</h1>
                    @if($institute->city)
                        <p class="text-white/70 text-sm flex items-center gap-1">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            {{ $institute->city }}{{ $institute->state ? ', '.$institute->state : '' }}
                        </p>
                    @endif
                </div>
            </div>

            @if($institute->description)
            <p class="text-white/85 text-sm leading-relaxed mb-6">{{ $institute->description }}</p>
            @endif

            <div class="space-y-2">
                @if($institute->phone)
                <div class="flex items-center gap-2 text-white/80 text-sm">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round"/></svg>
                    {{ $institute->phone }}
                </div>
                @endif
                @if($institute->contact_email)
                <div class="flex items-center gap-2 text-white/80 text-sm">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round"/></svg>
                    {{ $institute->contact_email }}
                </div>
                @endif
                @if($institute->established_year)
                <div class="flex items-center gap-2 text-white/80 text-sm">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round"/></svg>
                    Est. {{ $institute->established_year }}
                </div>
                @endif
            </div>
        </div>

        <p class="text-white/40 text-xs mt-8">Powered by <a href="/" class="underline">QuonixAI</a></p>
    </div>

    {{-- Right: Registration Form --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Create your account</h2>
            <p class="text-gray-500 text-sm mb-6">Register as a student at <strong>{{ $institute->name }}</strong></p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    <ul class="list-disc ml-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('student.register.store', $institute->slug) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input-field" placeholder="Your full name" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="you@example.com" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input-field" placeholder="+91 98765 43210" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="input-field" placeholder="Min. 8 chars" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" class="input-field" placeholder="Repeat" required>
                    </div>
                </div>
                <button type="submit" class="btn-brand mt-2">Join {{ $institute->name }} →</button>
            </form>

            <p class="text-xs text-center text-gray-400 mt-4">Already have an account? <a href="{{ route('login') }}" style="color:{{ $institute->brand_color ?? '#4f46e5' }}" class="font-semibold">Sign in</a></p>
        </div>
    </div>
</div>
</body>
</html>
