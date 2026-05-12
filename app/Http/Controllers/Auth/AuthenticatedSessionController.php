<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();
        // Path-based check: resolved_institute comes from ResolveInstitute middleware
        $institute = $request->get('resolved_institute');

        // 1. If on root domain (no institute resolved), handle redirection
        if (!$institute && $user->role !== 'superadmin') {
            $inst = $user->institute;
            if ($inst) {
                // If they logged in at global, just take them to their dashboard!
                return redirect()->route('dashboard', ['slug' => $inst->slug]);
            }
            Auth::guard('web')->logout();
            return redirect()->route('login.global')->with('error', 'Unauthorized access.');
        }

        // 2. If an institute is resolved, ensure user belongs to this institute
        if ($institute) {
            if ($user->role !== 'superadmin' && $user->institute_id !== $institute->id) {
                Auth::guard('web')->logout();
                return redirect()->route('login', ['slug' => $institute->slug])->with('error', 'You do not have access to this institute.');
            }
        }

        $request->session()->regenerate();

        if ($user->role === 'superadmin') {
             return redirect()->intended(route('superadmin.index'));
        }

        return redirect()->intended(route('dashboard', ['slug' => $institute->slug]));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $institute = $request->get('resolved_institute');
        $slug = $institute?->slug;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($slug) {
            return redirect()->route('login', ['slug' => $slug]);
        }

        return redirect()->route('login.global');
    }
}
