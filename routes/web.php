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
    $name = 'QuonixAI';
    $shortName = 'QuonixAI';
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

// Smart Registration: Institute registration on main domain, Student registration on subdomain
Route::get('/student/register', function (\Illuminate\Http\Request $request) {
    if ($request->has('resolved_institute')) {
        return app(App\Http\Controllers\StudentRegistrationController::class)->create($request->get('resolved_institute')->slug);
    }
    return redirect()->route('register');
})->name('student.register.portal');

Route::post('/student/register', function (\Illuminate\Http\Request $request) {
    if ($request->has('resolved_institute')) {
        return app(App\Http\Controllers\StudentRegistrationController::class)->store($request, $request->get('resolved_institute')->slug);
    }
    return redirect()->route('register');
});

Route::get('/register', function (\Illuminate\Http\Request $request) {
    if ($request->has('resolved_institute')) {
        return redirect('/student/register');
    }
    return app(App\Http\Controllers\Auth\RegisteredUserController::class)->create();
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    if ($request->has('resolved_institute')) {
        return redirect('/student/register');
    }
    return app(App\Http\Controllers\Auth\RegisteredUserController::class)->store($request);
});

// Legacy / Direct routes (kept for internal use)
Route::get('/register/student/{slug}', [App\Http\Controllers\StudentRegistrationController::class, 'create'])->name('student.register');
Route::post('/register/student/{slug}', [App\Http\Controllers\StudentRegistrationController::class, 'store'])->middleware('throttle:5,1')->name('student.register.store');

Route::middleware(['auth', 'verified', 'check.subscription'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin & Teacher Only Management
    Route::middleware(['admin'])->group(function () {
        Route::resource('batches', App\Http\Controllers\BatchController::class);
        Route::resource('teachers', App\Http\Controllers\TeacherController::class);
        Route::resource('students', App\Http\Controllers\StudentController::class);
        Route::resource('enquiries', App\Http\Controllers\EnquiryController::class);
        Route::resource('attendances', App\Http\Controllers\AttendanceController::class)->only(['index', 'create', 'store']);
        Route::post('/fees/{fee}/payments', [App\Http\Controllers\FeeController::class, 'addPayment'])->name('fees.payments.store');
        Route::resource('fees', App\Http\Controllers\FeeController::class);
        Route::get('fees/{fee}/receipt', [App\Http\Controllers\FeeController::class, 'receipt'])->name('fees.receipt');

        // AI Features
        Route::post('/ai/generate-questions', [App\Http\Controllers\AIController::class, 'generateQuestions'])->name('ai.generate-questions');
        Route::get('/ai/enquiries/{enquiry}/followup', [App\Http\Controllers\AIController::class, 'suggestFollowUp'])->name('ai.enquiries.followup');
        
        // Quizzes (Admin/Teacher)
        Route::resource('quizzes', App\Http\Controllers\QuizController::class);

        // Profile Requests (Approval side)
        Route::get('/profile-requests', [App\Http\Controllers\ProfileUpdateRequestController::class, 'index'])->name('profile_requests.index');
        Route::put('/profile-requests/{profileRequest}', [App\Http\Controllers\ProfileUpdateRequestController::class, 'update'])->name('profile_requests.update');

        // Announcements
        Route::post('/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Institute Settings
        Route::get('/settings', [App\Http\Controllers\InstituteController::class, 'settings'])->name('institute.settings');
        Route::put('/settings', [App\Http\Controllers\InstituteController::class, 'updateSettings'])->name('institute.settings.update');

        // Subscription management
        Route::get('/subscription', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('/subscription/create', [App\Http\Controllers\SubscriptionController::class, 'create'])->name('subscription.create');
        Route::post('/subscription/verify', [App\Http\Controllers\SubscriptionController::class, 'verify'])->name('subscription.verify');
        Route::get('/subscription/invoice/{id}', [App\Http\Controllers\SubscriptionController::class, 'showInvoice'])->name('subscription.invoice');
    });

    // Student Only Management
    Route::prefix('my-tests')->name('student.quizzes.')->group(function () {
        Route::get('/', [App\Http\Controllers\StudentQuizController::class, 'index'])->name('index');
        Route::get('/{quiz}/take', [App\Http\Controllers\StudentQuizController::class, 'take'])->name('take');
        Route::post('/{quiz}/submit', [App\Http\Controllers\StudentQuizController::class, 'submit'])->name('submit');
        Route::get('/result/{attempt}', [App\Http\Controllers\StudentQuizController::class, 'result'])->name('result');
    });

    // Study Materials
    Route::get('/study-materials', [App\Http\Controllers\StudyMaterialController::class, 'index'])->name('study_materials.index');
    Route::post('/study-materials', [App\Http\Controllers\StudyMaterialController::class, 'store'])->name('study_materials.store')->middleware('admin');
    Route::delete('/study-materials/{studyMaterial}', [App\Http\Controllers\StudyMaterialController::class, 'destroy'])->name('study_materials.destroy')->middleware('admin');

    // Shared / Student allowed
    Route::post('/profile-requests', [App\Http\Controllers\ProfileUpdateRequestController::class, 'store'])->name('profile_requests.store');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
});

// Super Admin (Strictly Protected)
Route::middleware(['auth', 'verified', 'superadmin'])->group(function () {
    Route::get('/superadmin/institutes', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::post('/superadmin/institutes/{institute}/toggle-lifetime-free', [App\Http\Controllers\SuperAdminController::class, 'toggleLifetimeFree'])->name('superadmin.toggle_lifetime_free');
    Route::get('/superadmin/impersonate/{institute}', [App\Http\Controllers\SuperAdminController::class, 'impersonate'])->name('superadmin.impersonate');
    Route::get('/superadmin/stop-impersonate', [App\Http\Controllers\SuperAdminController::class, 'stopImpersonating'])->name('superadmin.stop_impersonate');
    Route::post('/superadmin/broadcast', [App\Http\Controllers\SuperAdminController::class, 'broadcast'])->name('superadmin.broadcast');
    Route::post('/superadmin/settings', [App\Http\Controllers\SuperAdminController::class, 'updateSettings'])->name('superadmin.settings.update');
});

require __DIR__.'/auth.php';

// Public Fee Receipt Sharing
Route::get('/receipt/{token}', [App\Http\Controllers\FeeController::class, 'share'])->name('fees.share');
