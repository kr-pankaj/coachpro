<?php

namespace App\Traits;

trait HasTenantUrl
{
    /**
     * Generate a full URL for a tenant subdomain.
     */
    public function tenantRoute($institute, $path = '')
    {
        $appUrl = config('app.url');
        $host = parse_url($appUrl, PHP_URL_HOST);
        $port = parse_url($appUrl, PHP_URL_PORT);
        
        $baseHost = $host . ($port ? ':' . $port : '');
        $path = ltrim($path, '/');
        
        return 'http://' . $institute->slug . '.' . $baseHost . '/' . $path;
    }
}
