<x-guest-layout>
    <x-slot name="pageTitle">Find My Institute</x-slot>

    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-0.03em;margin-bottom:0.4rem;">
            Find Your Portal
        </h2>
        <p style="color:#64748b;font-size:0.9rem;">
            Enter your email address and we'll take you to your institute's login page.
        </p>
    </div>

    @if (session('error'))
        <div style="padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:0.625rem;margin-bottom:1.25rem;color:#991b1b;font-size:0.875rem;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('institute.find.post') }}" style="display:flex;flex-direction:column;gap:1.125rem;">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Registered Email address</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com" />
            </div>
        </div>

        <button type="submit" class="btn-primary" style="margin-top:0.25rem;">
            Take Me to My Institute →
        </button>
    </form>

    <p style="text-align:center;margin-top:1.5rem;font-size:0.875rem;color:#64748b;">
        Remembered your URL? 
        <a href="{{ route('welcome') }}" style="color:#4f46e5;font-weight:600;text-decoration:none;">Go back</a>
    </p>

    <div style="margin-top:1rem;text-align:center;">
        <a href="{{ route('superadmin.login') }}" style="font-size:0.75rem;color:#94a3b8;text-decoration:none;hover:color:#64748b;">Super Admin Portal</a>
    </div>

    <div style="margin-top:2rem;text-align:center;">
        <span style="font-size:0.75rem;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.1em;background:#f8fafc;padding:0.25rem 0.75rem;border-radius:999px;border:1px solid #f1f5f9;">Version {{ config('app.version') }}</span>
    </div>
</x-guest-layout>
