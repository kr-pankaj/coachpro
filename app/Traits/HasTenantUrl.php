<?php

namespace App\Traits;

trait HasTenantUrl
{
    /**
     * Generate a full URL for a tenant subdomain.
     */
    public function tenantRoute($institute, $path = '')
    {
        $appUrl = rtrim(config('app.url'), '/');
        $path = ltrim($path, '/');
        
        return $appUrl . '/' . $institute->slug . '/' . $path;
    }
}
