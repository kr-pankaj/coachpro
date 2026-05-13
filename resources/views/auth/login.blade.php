<x-guest-layout>
    <x-slot name="pageTitle">Sign In</x-slot>

    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-0.03em;margin-bottom:0.4rem;">
            @if(request('resolved_institute'))
                {{ request('resolved_institute')->name }}
            @else
                Welcome back
            @endif
        </h2>
        <p style="color:#64748b;font-size:0.9rem;">
            @if(request('resolved_institute'))
                Sign in to your institute portal to continue.
            @else
                Sign in to your {{ config('app.name') }} account to continue.
            @endif
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div style="padding:0.75rem 1rem;background:#ecfdf5;border:1px solid #6ee7b7;border-radius:0.625rem;margin-bottom:1.25rem;color:#065f46;font-size:0.875rem;">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div style="padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:0.625rem;margin-bottom:1.25rem;color:#991b1b;font-size:0.875rem;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ request()->route('slug') ? route('login', ['slug' => request()->route('slug')]) : route('superadmin.login') }}" style="display:flex;flex-direction:column;gap:1.125rem;">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Email address</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email', request('email')) }}" required autofocus autocomplete="username" placeholder="you@institute.com" />
            </div>
            @error('email')
                <p style="color:#dc2626;font-size:0.8rem;margin-top:0.375rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.375rem;">
                <label for="password" style="font-size:0.8125rem;font-weight:600;color:#374151;">Password</label>
                @if (request('resolved_institute') && Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:0.8rem;color:#4f46e5;text-decoration:none;font-weight:500;">Forgot password?</a>
                @endif
            </div>
            <div class="input-group" x-data="{ show: false }">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <input id="password" class="input-field" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" style="padding-right:3rem;" />
                <button type="button" @click="show=!show" style="position:absolute;right:0.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
                    <svg x-show="!show" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg x-show="show" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            @error('password')
                <p style="color:#dc2626;font-size:0.8rem;margin-top:0.375rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div style="display:flex;align-items:center;gap:0.5rem;">
            <input id="remember_me" type="checkbox" name="remember" style="width:1rem;height:1rem;accent-color:#4f46e5;cursor:pointer;">
            <label for="remember_me" style="font-size:0.875rem;color:#4b5563;cursor:pointer;">Keep me signed in</label>
        </div>

        <button type="submit" class="btn-primary" style="margin-top:0.25rem;">
            Sign In →
        </button>
    </form>

    @if(!request('resolved_institute'))
    <p style="text-align:center;margin-top:1.5rem;font-size:0.875rem;color:#64748b;">
        New to {{ config('app.name') }}? 
        <a href="{{ route('register') }}" style="color:#4f46e5;font-weight:600;text-decoration:none;">Create an account</a>
    </p>
    @else
    <p style="text-align:center;margin-top:1.5rem;font-size:0.875rem;color:#64748b;">
        Not your institute? 
        <a href="{{ route('login.global') }}" style="color:#4f46e5;font-weight:600;text-decoration:none;">Find my institute</a>
    </p>
    @endif

    <!-- <div style="margin-top:1.5rem;padding:1rem;background:#f0f9ff;border:1px solid #bae6fd;border-radius:0.625rem;">
        <p style="font-size:0.75rem;color:#0369a1;font-weight:600;margin-bottom:0.375rem;">ℹ How login works</p>
        <p style="font-size:0.75rem;color:#0c4a6e;line-height:1.5;">
            The same login form is used by everyone. Your role (Institute Admin, Student, or Super Admin) is automatically detected from your account type.
        </p>
    </div> -->
    <div style="margin-top:2rem;text-align:center;">
        <span style="font-size:0.75rem;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.1em;background:#f8fafc;padding:0.25rem 0.75rem;border-radius:999px;border:1px solid #f1f5f9;">Version {{ config('app.version') }}</span>
    </div>
</x-guest-layout>
