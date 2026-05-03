<?php

/**
 * QuonixAI v1.0.6.0 - Shared Hosting Deployment Tool
 * Purpose: Run Artisan commands via browser when SSH is restricted.
 * SECURITY: Delete this file immediately after use.
 */

// Basic Security Check
$secret = 'quonix_deploy_2026';
if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    die('Unauthorized access. Please provide the correct deployment key.');
}

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function runArtisan($command) {
    echo "<li>Running: <strong>php artisan $command</strong>... ";
    try {
        \Illuminate\Support\Facades\Artisan::call($command);
        echo "<span style='color:green;'>DONE</span></li>";
    } catch (\Exception $e) {
        echo "<span style='color:red;'>FAILED</span><br><small>" . $e->getMessage() . "</small></li>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>QuonixAI Deployer v1.0.6.0</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: white; padding: 3rem; }
        .card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 2rem; padding: 2rem; max-width: 600px; margin: auto; }
        h1 { color: #4f46e5; margin-bottom: 2rem; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.5rem; }
        .btn { display: inline-block; background: #4f46e5; color: white; padding: 0.75rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: bold; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>QuonixAI Deployment Hub</h1>
        <p>Target: <strong>v1.0.6.0 Production Rollout</strong></p>
        
        <ul>
            <?php
                runArtisan('migrate --force');
                runArtisan('storage:link');
                runArtisan('optimize:clear');
                runArtisan('view:cache');
                runArtisan('config:cache');
                runArtisan('route:cache');
            ?>
        </ul>

        <div style="margin-top: 2rem; padding: 1rem; background: rgba(239,68,68,0.1); border: 1px solid #ef4444; border-radius: 1rem;">
            <strong style="color:#ef4444;">IMPORTANT:</strong> Delete this file (<code>deploy_hostinger.php</code>) from your server immediately for security.
        </div>

        <a href="/" class="btn">Go to Application →</a>
    </div>
</body>
</html>
