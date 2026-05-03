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
        $isSubdomain = $request->has('resolved_institute');

        // 1. If on main domain, only allow Super Admin
        if (!$isSubdomain && $user->role !== 'superadmin') {
            Auth::guard('web')->logout();
            $inst = $user->institute;
            if ($inst) {
                $appUrl = config('app.url');
                $host = parse_url($appUrl, PHP_URL_HOST);
                $port = parse_url($appUrl, PHP_URL_PORT);
                $baseHost = $host . ($port ? ':' . $port : '');
                $correctUrl = 'http://' . $inst->slug . '.' . $baseHost . '/login';
                return redirect($correctUrl)->with('error', 'Please log in through your institute portal.');
            }
            return redirect()->route('login')->with('error', 'Unauthorized domain access.');
        }

        // 2. If on subdomain, ensure user belongs to this institute
        if ($isSubdomain) {
            $institute = $request->get('resolved_institute');
            if ($user->role !== 'superadmin' && $user->institute_id !== $institute->id) {
                Auth::guard('web')->logout();
                return redirect()->route('login')->with('error', 'You do not have access to this institute.');
            }
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
