<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Routes that are always allowed regardless of subscription status.
     */
    protected array $allowedRoutes = [
        'subscription.index',
        'subscription.create',
        'subscription.verify',
        'institute.settings',
        'institute.settings.update',
        'profile.edit',
        'profile.update',
        'profile.destroy',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated institute admins
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return $next($request);
        }

        $institute = auth()->user()->institute;
        if (!$institute) {
            return $next($request);
        }

        // Lifetime free — always allowed
        if ($institute->is_lifetime_free) {
            return $next($request);
        }

        // Active subscription — always allowed
        if (!empty($institute->razorpay_subscription_id)) {
            return $next($request);
        }

        // Still in trial — always allowed
        $trialExpired = $institute->created_at->addDays(14)->isPast();
        if (!$trialExpired) {
            return $next($request);
        }

        // --- Trial has expired & no subscription ---

        // Always allow access to the allowed routes above (settings, subscription, logout)
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, $this->allowedRoutes)) {
            return $next($request);
        }

        // For any other route, redirect to subscription page
        return redirect()->route('subscription.index')
            ->with('error', 'Your free trial has expired. Please subscribe to continue using CoachPro.');
    }
}
