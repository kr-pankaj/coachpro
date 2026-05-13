<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login.global');
        }

        $user = auth()->user();

        // Superadmin and Admin generally bypass these checks in most cases, 
        // but if we explicitly check, we let admin pass if they have the rights.
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Admin has access to all institute routes
        if ($user->role === 'admin' && in_array('admin', $roles)) {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access based on your role.');
        }

        return $next($request);
    }
}
