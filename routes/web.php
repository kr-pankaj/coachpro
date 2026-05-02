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

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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

});

// Super Admin (Always accessible for maintenance)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/superadmin/institutes', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::post('/superadmin/institutes/{institute}/toggle-lifetime-free', [App\Http\Controllers\SuperAdminController::class, 'toggleLifetimeFree'])->name('superadmin.toggle_lifetime_free');
    Route::get('/superadmin/impersonate/{institute}', [App\Http\Controllers\SuperAdminController::class, 'impersonate'])->name('superadmin.impersonate');
    Route::get('/superadmin/stop-impersonate', [App\Http\Controllers\SuperAdminController::class, 'stopImpersonating'])->name('superadmin.stop_impersonate');
    Route::post('/superadmin/broadcast', [App\Http\Controllers\SuperAdminController::class, 'broadcast'])->name('superadmin.broadcast');
});

require __DIR__.'/auth.php';
