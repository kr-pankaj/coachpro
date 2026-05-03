<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$student = \App\Models\Student::find(201);
if ($student && !$student->user_id) {
    $user = \App\Models\User::create([
        'name' => $student->name,
        'email' => 'ishan.test@quonix.io',
        'password' => bcrypt('password'),
        'role' => 'student',
        'institute_id' => $student->institute_id
    ]);
    $student->user_id = $user->id;
    $student->save();
    echo "User created and linked for Ishan (ID: 201)!\n";
} else if ($student) {
    echo "Ishan already has a user (User ID: " . $student->user_id . ")\n";
} else {
    echo "Student ID 201 not found.\n";
}
