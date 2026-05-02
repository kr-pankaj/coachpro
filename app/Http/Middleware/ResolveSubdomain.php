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
        
        // Check if the current host is a subdomain of the base host
        if ($httpHost !== $baseHost && Str::endsWith($httpHost, '.' . $baseHost)) {
            $subdomain = Str::before($httpHost, '.' . $baseHost);
            
            $institute = \App\Models\Institute::where('slug', $subdomain)->first();
            
            if ($institute) {
                // Attach the resolved institute to the request for easy access
                $request->merge(['resolved_institute' => $institute]);
                
                // If hitting the root '/' on a subdomain, redirect to login
                if ($request->is('/')) {
                    return redirect()->route('login');
                }

                // If hitting the general '/register' on a subdomain, block it
                if ($request->is('register')) {
                    return redirect()->route('login')->with('error', 'General registration is not allowed on this portal.');
                }

                // If the user is logged in, verify they belong to this institute
                if (auth()->check() && auth()->user()->role !== 'superadmin' && auth()->user()->institute_id !== $institute->id) {
                    auth()->logout();
                    return redirect()->route('login')->with('error', 'You do not have access to this institute.');
                }
            }
        }

        return $next($request);
    }
}
