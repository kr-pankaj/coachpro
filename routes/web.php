<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/offline', function () {
    return 'You are currently offline. Please check your internet connection.';
});

Route::get('/manifest.json', function () {
    $name = 'CoachPro';
    $shortName = 'CoachPro';
    $color = '#4f46e5';
    $logo = '/icon-192.png'; // Fallback icon

    if (auth()->check() && auth()->user()->institute) {
        $inst = auth()->user()->institute;
        $name = $inst->name;
        $shortName = strlen($inst->name) > 12 ? substr($inst->name, 0, 12) : $inst->name;
        $color = $inst->brand_color ?? '#4f46e5';
        if ($inst->logo_url) {
            $logo = $inst->logo_url;
        }
    }

    return response()->json([
        'name' => $name,
        'short_name' => $shortName,
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => $color,
        'icons' => [
            [
                'src' => $logo,
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
            [
                'src' => $logo,
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ]
        ]
    ]);
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'superadmin') {
        return redirect()->route('superadmin.index');
    }

    if (auth()->user()->role === 'student') {
        $student = \App\Models\Student::where('user_id', auth()->id())->first();
        if (!$student) return view('dashboard');
        $fees        = \App\Models\Fee::where('student_id', $student->id)->orderBy('month_year', 'desc')->get();
        $attendances = \App\Models\Attendance::where('student_id', $student->id)->orderBy('date', 'desc')->take(30)->get();
        return view('student.dashboard', compact('student', 'fees', 'attendances'));
    }

    // Admin dashboard
    $institute    = auth()->user()->institute;
    $totalStudents = \App\Models\Student::count();
    $totalBatches  = \App\Models\Batch::count();

    $todayAttended = \App\Models\Attendance::whereDate('date', today())->where('status', 'present')->count();
    $todayTotal    = \App\Models\Attendance::whereDate('date', today())->count();

    $pendingFees   = \App\Models\Fee::where('status', 'pending')->sum('amount');
    $collectedFees = \App\Models\Fee::where('status', 'paid')->sum('amount');

    $recentStudents = \App\Models\Student::with('batch')->latest()->take(5)->get();
    $batches        = \App\Models\Batch::withCount('students')->get();

    $announcements = \App\Models\Announcement::where('institute_id', $institute->id)
        ->where(fn($q) => $q->whereNull('expires_on')->orWhere('expires_on', '>=', today()))
        ->orderByDesc('created_at')->take(5)->get();

    $profilePct    = $institute->profileCompletion();

    return view('dashboard', compact(
        'institute', 'totalStudents', 'totalBatches',
        'todayAttended', 'todayTotal', 'pendingFees', 'collectedFees',
        'recentStudents', 'batches', 'announcements', 'profilePct'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/register/student/{slug}', [App\Http\Controllers\StudentRegistrationController::class, 'create'])->name('student.register');
Route::post('/register/student/{slug}', [App\Http\Controllers\StudentRegistrationController::class, 'store'])->name('student.register.store');

Route::middleware(['auth', 'verified', 'check.subscription'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('batches', App\Http\Controllers\BatchController::class);
    Route::resource('teachers', App\Http\Controllers\TeacherController::class);
    Route::resource('students', App\Http\Controllers\StudentController::class);
    Route::resource('enquiries', App\Http\Controllers\EnquiryController::class);
    Route::resource('attendances', App\Http\Controllers\AttendanceController::class)->only(['index', 'create', 'store']);
    Route::resource('fees', App\Http\Controllers\FeeController::class);
    Route::get('fees/{fee}/receipt', [App\Http\Controllers\FeeController::class, 'receipt'])->name('fees.receipt');

    // Quizzes (Admin/Teacher)
    Route::resource('quizzes', App\Http\Controllers\QuizController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Student Quiz Portal
    Route::prefix('my-tests')->name('student.quizzes.')->group(function () {
        Route::get('/', [App\Http\Controllers\StudentQuizController::class, 'index'])->name('index');
        Route::get('/{quiz}/take', [App\Http\Controllers\StudentQuizController::class, 'take'])->name('take');
        Route::post('/{quiz}/submit', [App\Http\Controllers\StudentQuizController::class, 'submit'])->name('submit');
        Route::get('/result/{attempt}', [App\Http\Controllers\StudentQuizController::class, 'result'])->name('result');
    });

    // Profile Requests
    Route::get('/profile-requests', [App\Http\Controllers\ProfileUpdateRequestController::class, 'index'])->name('profile_requests.index');
    Route::post('/profile-requests', [App\Http\Controllers\ProfileUpdateRequestController::class, 'store'])->name('profile_requests.store');
    Route::put('/profile-requests/{profileRequest}', [App\Http\Controllers\ProfileUpdateRequestController::class, 'update'])->name('profile_requests.update');

    // Announcements
    Route::post('/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Institute Settings
    Route::get('/settings', [App\Http\Controllers\InstituteController::class, 'settings'])->name('institute.settings');
    Route::put('/settings', [App\Http\Controllers\InstituteController::class, 'updateSettings'])->name('institute.settings.update');

    // Subscription
    Route::get('/subscription', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/create', [App\Http\Controllers\SubscriptionController::class, 'create'])->name('subscription.create');
    Route::post('/subscription/verify', [App\Http\Controllers\SubscriptionController::class, 'verify'])->name('subscription.verify');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

    // Super Admin
    Route::get('/superadmin/institutes', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::post('/superadmin/institutes/{institute}/toggle-lifetime-free', [App\Http\Controllers\SuperAdminController::class, 'toggleLifetimeFree'])->name('superadmin.toggle_lifetime_free');
});

require __DIR__.'/auth.php';
