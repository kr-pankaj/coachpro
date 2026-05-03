<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$students = \App\Models\Student::where('name', 'like', '%Ishan%')->get();
if ($students->count() > 0) {
    foreach ($students as $student) {
        echo "ID: " . $student->id . "\n";
        echo "Name: " . $student->name . "\n";
        echo "Has User: " . ($student->user ? "Yes" : "No") . "\n";
        echo "Email: " . ($student->user ? $student->user->email : "N/A") . "\n";
        
        $attendance = \App\Models\Attendance::where('student_id', $student->id)->latest()->first();
        echo "Latest Attendance Status: " . ($attendance ? $attendance->status : "None") . "\n";
        echo "Latest Attendance Date: " . ($attendance ? $attendance->date : "None") . "\n";
        echo "---------------------------\n";
    }
} else {
    echo "No students found containing 'Ishan'.\n";
}
