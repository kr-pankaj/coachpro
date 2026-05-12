<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Institute;
use Illuminate\Support\Facades\URL;

class ResolveInstitute
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');

        if ($slug) {
            $institute = Institute::where('slug', $slug)->first();

            if (!$institute) {
                abort(404, 'Institute not found.');
            }

            // Share the institute globally
            $request->merge(['resolved_institute' => $institute]);
            view()->share('resolved_institute', $institute);

            // Set URL default so route() always works
            URL::defaults(['slug' => $slug]);

            // IMPORTANT: Remove slug from route parameters so controllers don't need to change
            $request->route()->forgetParameter('slug');

            // Verify access if logged in
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->role !== 'superadmin' && $user->institute_id !== $institute->id) {
                    auth()->logout();
                    return redirect()->route('login', ['slug' => $slug])->with('error', 'Unauthorized access to this institute.');
                }
            }
        } else {
            // No slug in URL, but if user is logged in and belongs to an institute, set defaults anyway
            if (auth()->check() && auth()->user()->institute) {
                URL::defaults(['slug' => auth()->user()->institute->slug]);
                view()->share('resolved_institute', auth()->user()->institute);
            }
        }

        return $next($request);
    }
}
