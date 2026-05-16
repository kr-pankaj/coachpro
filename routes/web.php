<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 1. GLOBAL ROUTES (No Slug)
// =========================================================================

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/offline', function () {
    return 'You are currently offline.';
});

Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/terms-and-conditions', 'terms')->name('terms');

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
        return redirect()->route('login', ['slug' => $user->institute->slug, 'email' => $request->email]);
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
Route::post('/contact', [App\Http\Controllers\ContactLeadController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:3,1');

Route::get('/superadmin/stop-impersonate', [App\Http\Controllers\SuperAdminController::class, 'stopImpersonating'])
    ->name('superadmin.stop_impersonate')
    ->middleware('auth');

// Super Admin Portal
Route::middleware(['auth', 'verified', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/institutes', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::post('/institutes/{institute}/toggle-lifetime-free', [App\Http\Controllers\SuperAdminController::class, 'toggleLifetimeFree'])->name('superadmin.toggle_lifetime_free');
    Route::get('/impersonate/{institute}', [App\Http\Controllers\SuperAdminController::class, 'impersonate'])->name('superadmin.impersonate');
    Route::post('/broadcast', [App\Http\Controllers\SuperAdminController::class, 'broadcast'])->name('superadmin.broadcast');
    Route::post('/settings', [App\Http\Controllers\SuperAdminController::class, 'updateSettings'])->name('superadmin.settings.update');
    Route::get('/settings', [App\Http\Controllers\SuperAdminController::class, 'settings'])->name('superadmin.settings');
    // Contact Leads
    Route::get('/contact-leads', [App\Http\Controllers\ContactLeadController::class, 'index'])->name('superadmin.contact-leads.index');
    Route::put('/contact-leads/{contactLead}', [App\Http\Controllers\ContactLeadController::class, 'update'])->name('superadmin.contact-leads.update');
    // Marketplace Management
    Route::get('/add-ons', [App\Http\Controllers\SuperAdminController::class, 'manageAddOns'])->name('superadmin.add_ons.index');
    Route::post('/add-ons', [App\Http\Controllers\SuperAdminController::class, 'storeAddOn'])->name('superadmin.add_ons.store');
    Route::post('/add-ons/{addOn}/toggle-promotion', [App\Http\Controllers\SuperAdminController::class, 'toggleAddOnPromotion'])->name('superadmin.add_ons.toggle_promotion');

    // Knowledge Base Management
    Route::resource('kb-categories', App\Http\Controllers\KbCategoryController::class)->names('superadmin.kb-categories');
    Route::resource('kb-articles', App\Http\Controllers\KbArticleController::class)->names('superadmin.kb-articles');
});

// Knowledge Base Public Routes
Route::get('/help', [App\Http\Controllers\KnowledgeBaseController::class, 'index'])->name('kb.index');
Route::get('/help/search', [App\Http\Controllers\KnowledgeBaseController::class, 'search'])->name('kb.search');
Route::get('/help/category/{category_slug}', [App\Http\Controllers\KnowledgeBaseController::class, 'category'])->name('kb.category');
Route::get('/help/article/{article_slug}', [App\Http\Controllers\KnowledgeBaseController::class, 'show'])->name('kb.show');
Route::post('/help/article/{article}/feedback', [App\Http\Controllers\KnowledgeBaseController::class, 'feedback'])->name('kb.feedback');

// Global Auth Routes (No Slug needed)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Global password update route for users without an institute (e.g. Superadmin)
    Route::put('/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update.global');
});


// =========================================================================
// 2. INSTITUTE ROUTES (Prefixed with {slug})
// =========================================================================

Route::prefix('{slug}')->group(function () {
    
    // Auth Routes (Login, Logout, etc.)
    require __DIR__.'/auth.php';

    // Student Registration
    Route::get('/student/register', [App\Http\Controllers\StudentRegistrationController::class, 'create'])->name('student.register.portal');
    Route::post('/student/register', [App\Http\Controllers\StudentRegistrationController::class, 'store'])
        ->name('student.register.store')
        ->middleware('throttle:3,1');

    // Main Institute Management Group
    Route::middleware(['auth', 'verified', 'check.subscription'])->group(function () {
        
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        // -------------------------------------------------------------
        // Core Management (Admin, Teacher, Receptionist)
        // -------------------------------------------------------------
        Route::middleware(['role:admin,teacher,receptionist'])->group(function () {
            Route::resource('students', App\Http\Controllers\StudentController::class);
            Route::get('students/{student}/id-card', [App\Http\Controllers\StudentController::class, 'generateIdCard'])->name('students.id-card');
            Route::resource('attendances', App\Http\Controllers\AttendanceController::class)->only(['index', 'create', 'store']);
        });

        // -------------------------------------------------------------
        // Academic Management (Admin, Teacher)
        // -------------------------------------------------------------
        Route::middleware(['role:admin,teacher'])->group(function () {
            Route::resource('batches', App\Http\Controllers\BatchController::class);
            Route::resource('quizzes', App\Http\Controllers\QuizController::class);
            
            // AI Features
            Route::post('/ai/generate-questions', [App\Http\Controllers\AIController::class, 'generateQuestions'])
                ->name('ai.generate-questions')
                ->middleware('throttle:5,1');
        });

        // -------------------------------------------------------------
        // Finance Management (Admin, Accountant)
        // -------------------------------------------------------------
        Route::middleware(['role:admin,accountant'])->group(function () {
            Route::resource('fees', App\Http\Controllers\FeeController::class);
            Route::post('/fees/{fee}/payments', [App\Http\Controllers\FeeController::class, 'addPayment'])->name('fees.payments.store');
            Route::get('fees/{fee}/receipt', [App\Http\Controllers\FeeController::class, 'receipt'])->name('fees.receipt');
            Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
        });

        // -------------------------------------------------------------
        // Leads & Enquiries (Admin, Receptionist)
        // -------------------------------------------------------------
        Route::middleware(['role:admin,receptionist'])->group(function () {
            Route::resource('enquiries', App\Http\Controllers\EnquiryController::class);
            Route::get('/ai/enquiries/{enquiry}/followup', [App\Http\Controllers\AIController::class, 'suggestFollowUp'])
                ->name('ai.enquiries.followup')
                ->middleware('throttle:5,1');
            Route::post('/enquiries/{enquiry}/send-email', [App\Http\Controllers\EnquiryController::class, 'sendEmail'])->name('enquiries.send-email');
        });

        // -------------------------------------------------------------
        // Administration (Admin Only)
        // -------------------------------------------------------------
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('staff', App\Http\Controllers\StaffController::class);

            // Profile Requests
            Route::get('/profile-requests', [App\Http\Controllers\ProfileUpdateRequestController::class, 'index'])->name('profile_requests.index');
            Route::put('/profile-requests/{profileRequest}', [App\Http\Controllers\ProfileUpdateRequestController::class, 'update'])->name('profile_requests.update');

            // Announcements
            Route::post('/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
            Route::delete('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

            // Settings
            Route::get('/settings', [App\Http\Controllers\InstituteController::class, 'settings'])->name('institute.settings');
            Route::put('/settings', [App\Http\Controllers\InstituteController::class, 'updateSettings'])->name('institute.settings.update');

            // Certificates
            Route::get('/certificates/settings', [App\Http\Controllers\CertificateController::class, 'settings'])->name('certificates.settings');
            Route::post('/certificates/settings', [App\Http\Controllers\CertificateController::class, 'updateSettings'])->name('certificates.settings.update');
            Route::get('/certificates/preview', [App\Http\Controllers\CertificateController::class, 'preview'])->name('certificates.preview');
            Route::get('/certificates/issue/{student}', [App\Http\Controllers\CertificateController::class, 'issue'])->name('certificates.issue');
            Route::get('/certificates/download/{issuedCertificate}', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');

            // Subscription
            Route::get('/subscription', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
            Route::post('/subscription/create', [App\Http\Controllers\SubscriptionController::class, 'create'])->name('subscription.create');
            Route::post('/subscription/verify', [App\Http\Controllers\SubscriptionController::class, 'verify'])->name('subscription.verify');
            Route::get('/subscription/invoice/{id}', [App\Http\Controllers\SubscriptionController::class, 'showInvoice'])->name('subscription.invoice');

            // Marketplace (Powerups)
            Route::get('/marketplace', [App\Http\Controllers\AddOnMarketplaceController::class, 'index'])->name('marketplace.index');
            Route::post('/marketplace/purchase/{addOn}', [App\Http\Controllers\AddOnMarketplaceController::class, 'purchase'])->name('marketplace.purchase');
            Route::post('/marketplace/verify', [App\Http\Controllers\AddOnMarketplaceController::class, 'verify'])->name('marketplace.verify');
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
