<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 1. GLOBAL ROUTES (No Slug)
// =========================================================================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/offline', function () {
    return 'You are currently offline.';
});

// Global Login Handler (Find My Institute)
Route::get('/login', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'superadmin') return redirect()->route('superadmin.index');
        if (auth()->user()->institute) return redirect()->route('dashboard', ['slug' => auth()->user()->institute->slug]);
    }
    return view('auth.find-institute');
})->name('login.global');

// Find My Institute (Email Lookup)
Route::post('/find-institute', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $user = \App\Models\User::where('email', $request->email)->first();

    if ($user && $user->institute) {
        return redirect()->route('login', ['slug' => $user->institute->slug]);
    }

    if ($user && $user->role === 'superadmin') {
        return redirect()->route('superadmin.login');
    }

    return back()->with('error', 'No institute found for this email address.')->withInput();
})->name('institute.find.post');

// Dedicated Super Admin Login
Route::get('/admin/super-portal', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('superadmin.login');

Route::post('/admin/super-portal', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

// Institute Registration
Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

// Public Receipt Sharing
Route::get('/receipt/{token}', [App\Http\Controllers\FeeController::class, 'share'])->name('fees.share');

// Public Contact Form Submission
Route::post('/contact', [App\Http\Controllers\ContactLeadController::class, 'store'])->name('contact.store');

// Super Admin Portal
Route::middleware(['auth', 'verified', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/institutes', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::post('/institutes/{institute}/toggle-lifetime-free', [App\Http\Controllers\SuperAdminController::class, 'toggleLifetimeFree'])->name('superadmin.toggle_lifetime_free');
    Route::get('/impersonate/{institute}', [App\Http\Controllers\SuperAdminController::class, 'impersonate'])->name('superadmin.impersonate');
    Route::get('/stop-impersonate', [App\Http\Controllers\SuperAdminController::class, 'stopImpersonating'])->name('superadmin.stop_impersonate');
    Route::post('/broadcast', [App\Http\Controllers\SuperAdminController::class, 'broadcast'])->name('superadmin.broadcast');
    Route::post('/settings', [App\Http\Controllers\SuperAdminController::class, 'updateSettings'])->name('superadmin.settings.update');
    // Contact Leads
    Route::get('/contact-leads', [App\Http\Controllers\ContactLeadController::class, 'index'])->name('superadmin.contact-leads.index');
    Route::put('/contact-leads/{contactLead}', [App\Http\Controllers\ContactLeadController::class, 'update'])->name('superadmin.contact-leads.update');
});

// Global Auth Routes (No Slug needed)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =========================================================================
// 2. INSTITUTE ROUTES (Prefixed with {slug})
// =========================================================================

Route::prefix('{slug}')->group(function () {
    
    // Auth Routes (Login, Logout, etc.)
    require __DIR__.'/auth.php';

    // Student Registration
    Route::get('/student/register', [App\Http\Controllers\StudentRegistrationController::class, 'create'])->name('student.register.portal');
    Route::post('/student/register', [App\Http\Controllers\StudentRegistrationController::class, 'store'])->name('student.register.store');

    // Main Institute Management Group
    Route::middleware(['auth', 'verified', 'check.subscription'])->group(function () {
        
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        // Admin & Teacher Only Management
        Route::middleware(['admin'])->group(function () {
            Route::resource('batches', App\Http\Controllers\BatchController::class);
            Route::resource('teachers', App\Http\Controllers\TeacherController::class);
            Route::resource('students', App\Http\Controllers\StudentController::class);
            Route::get('students/{student}/id-card', [App\Http\Controllers\StudentController::class, 'generateIdCard'])->name('students.id-card');
            Route::resource('enquiries', App\Http\Controllers\EnquiryController::class);
            Route::resource('attendances', App\Http\Controllers\AttendanceController::class)->only(['index', 'create', 'store']);
            Route::post('/fees/{fee}/payments', [App\Http\Controllers\FeeController::class, 'addPayment'])->name('fees.payments.store');
            Route::resource('fees', App\Http\Controllers\FeeController::class);
            Route::get('fees/{fee}/receipt', [App\Http\Controllers\FeeController::class, 'receipt'])->name('fees.receipt');

            // AI Features
            Route::post('/ai/generate-questions', [App\Http\Controllers\AIController::class, 'generateQuestions'])->name('ai.generate-questions');
            Route::get('/ai/enquiries/{enquiry}/followup', [App\Http\Controllers\AIController::class, 'suggestFollowUp'])->name('ai.enquiries.followup');
            
            // Quizzes
            Route::resource('quizzes', App\Http\Controllers\QuizController::class);

            // Profile Requests
            Route::get('/profile-requests', [App\Http\Controllers\ProfileUpdateRequestController::class, 'index'])->name('profile_requests.index');
            Route::put('/profile-requests/{profileRequest}', [App\Http\Controllers\ProfileUpdateRequestController::class, 'update'])->name('profile_requests.update');

            // Announcements
            Route::post('/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
            Route::delete('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

            // Settings
            Route::get('/settings', [App\Http\Controllers\InstituteController::class, 'settings'])->name('institute.settings');
            Route::put('/settings', [App\Http\Controllers\InstituteController::class, 'updateSettings'])->name('institute.settings.update');

            // Subscription
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
        Route::get('/leaderboard', [App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard');
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
    });
});
