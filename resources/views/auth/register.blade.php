<x-guest-layout>
    <x-slot name="pageTitle">Register Institute</x-slot>

    <div style="margin-bottom:1.75rem;">
        <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-0.03em;margin-bottom:0.4rem;">Start your free trial</h2>
        <p style="color:#64748b;font-size:0.9rem;">14 days free, no credit card required to get started.</p>
    </div>


    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1rem;">
        @csrf

        <!-- Institute Name -->
        <div>
            <label for="institute_name" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Institute / Coaching Centre Name</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <input id="institute_name" class="input-field" type="text" name="institute_name" value="{{ old('institute_name') }}" required autofocus placeholder="e.g. Sharma Coaching Classes" />
            </div>
            @error('institute_name')
                <p style="color:#dc2626;font-size:0.8rem;margin-top:0.375rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Admin Name -->
        <div>
            <label for="name" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Your Name (Admin)</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <input id="name" class="input-field" type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name" />
            </div>
            @error('name')
                <p style="color:#dc2626;font-size:0.8rem;margin-top:0.375rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Email address</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required placeholder="you@institute.com" />
            </div>
            @error('email')
                <p style="color:#dc2626;font-size:0.8rem;margin-top:0.375rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password row -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div>
                <label for="password" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Password</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round"/></svg>
                    </span>
                    <input id="password" class="input-field" type="password" name="password" required placeholder="Min. 8 chars" />
                </div>
                @error('password')
                    <p style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" style="display:block;font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Confirm</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round"/></svg>
                    </span>
                    <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required placeholder="Repeat password" />
                </div>
            </div>
        </div>

        <!-- Terms -->
        <div style="display:flex;align-items:flex-start;gap:0.5rem;margin-top:0.25rem;">
            <input type="checkbox" id="terms" required style="margin-top:0.2rem;width:1rem;height:1rem;accent-color:#4f46e5;cursor:pointer;flex-shrink:0;">
            <label for="terms" style="font-size:0.8rem;color:#64748b;cursor:pointer;line-height:1.5;">
                I agree to the <a href="#" style="color:#4f46e5;font-weight:600;">Terms of Service</a> and <a href="#" style="color:#4f46e5;font-weight:600;">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" class="btn-primary" style="margin-top:0.25rem;">
            Create Account & Start Trial →
        </button>
    </form>

    <p style="text-align:center;margin-top:1.25rem;font-size:0.875rem;color:#64748b;">
        Already have an account? 
        <a href="{{ route('login') }}" style="color:#4f46e5;font-weight:600;text-decoration:none;">Sign in</a>
    </p>

    <div style="display:flex;align-items:center;gap:0.75rem;margin-top:1.25rem;padding:0.875rem;background:#f8fafc;border-radius:0.625rem;border:1px solid #e2e8f0;">
        <div style="background:#dcfce7;border-radius:50%;width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div>
            <p style="font-size:0.78rem;font-weight:600;color:#15803d;margin-bottom:0.1rem;">14-day free trial included</p>
            <p style="font-size:0.75rem;color:#4b5563;">You'll be prompted to choose a plan after registration. Cancel anytime.</p>
        </div>
    </div>
</x-guest-layout>
