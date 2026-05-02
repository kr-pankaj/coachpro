<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'institute_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $institute = \App\Models\Institute::create([
            'name' => $request->institute_name,
            'slug' => \Illuminate\Support\Str::slug($request->institute_name) . '-' . uniqid(),
            'allow_student_self_registration' => false,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'institute_id' => $institute->id,
            'role' => 'admin',
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Send welcome email (logs to storage/logs in local env)
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeInstituteMail($institute, $request->name));
        } catch (\Exception $e) {
            // Silently fail — don't block registration if email fails
        }

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to CoachPro! Your 14-day free trial has started. Explore all features freely.');
    }
}
