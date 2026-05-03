<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ResolveSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $httpHost = $request->getHttpHost(); // e.g. ica.localhost:8000
        $appUrl = config('app.url');
        $appHost = parse_url($appUrl, PHP_URL_HOST);
        $appPort = parse_url($appUrl, PHP_URL_PORT);
        $baseHost = $appHost . ($appPort ? ':' . $appPort : ''); // e.g. localhost:8000
        
        $isSubdomain = ($httpHost !== $baseHost && Str::endsWith($httpHost, '.' . $baseHost));

        if ($isSubdomain) {
            $subdomain = Str::before($httpHost, '.' . $baseHost);
            $institute = \App\Models\Institute::where('slug', $subdomain)->first();
            
            if (!$institute && $subdomain !== 'www') {
                abort(404, 'Institute not found.');
            }

            if ($institute) {
                $request->merge(['resolved_institute' => $institute]);
                
                if ($request->is('/')) {
                    return redirect()->route('login');
                }

                if ($request->is('register')) {
                    return redirect()->route('student.register.portal');
                }

                // If logged in, verify institute access
                if (auth()->check()) {
                    $user = auth()->user();
                    if ($user->role !== 'superadmin' && $user->institute_id !== $institute->id) {
                        auth()->logout();
                        return redirect()->route('login')->with('error', 'Unauthorized access to this institute.');
                    }
                }
            }
        } else {
            // ON MAIN DOMAIN: Restrict access
            
            // 1. Allow Super Admin Portal
            if ($request->is('admin/super-portal')) {
                return $next($request);
            }

            // 2. Block standard login on main domain for non-superadmins
            if ($request->is('login') && !$request->isMethod('POST')) {
                // If it's a GET login request on main domain, we show a message or redirect
                // But for now, we'll let it show, and block on POST.
            }

            if (auth()->check()) {
                $user = auth()->user();
                // Block everyone except Super Admin from main domain dashboard/profile
                if ($user->role !== 'superadmin') {
                    $inst = $user->institute;
                    auth()->logout();
                    
                    if ($inst) {
                        $correctUrl = 'http://' . $inst->slug . '.' . $baseHost . '/login';
                        return redirect($correctUrl)->with('error', 'Please log in through your institute portal.');
                    }
                    
                    return redirect()->route('login')->with('error', 'This domain is reserved for administration.');
                }
            }
        }

        return $next($request);
    }
}
