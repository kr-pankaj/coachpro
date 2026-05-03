<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Institute;
use App\Models\User;

$inst = Institute::first();
$admin = User::where('institute_id', $inst->id)->where('role', 'admin')->first();

if (!$admin) {
    // Create a test admin if none exists
    $admin = User::create([
        'name' => 'QA Admin',
        'email' => 'qa_admin@quonixai.io',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'institute_id' => $inst->id
    ]);
}

echo "URL: http://{$inst->slug}.localhost:8000\n";
echo "ADMIN_EMAIL: {$admin->email}\n";
echo "ADMIN_PASS: password (assumed)\n";
