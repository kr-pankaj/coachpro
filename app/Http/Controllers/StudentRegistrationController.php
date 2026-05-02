<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class StudentRegistrationController extends Controller
{
    public function create($slug)
    {
        $institute = Institute::where('slug', $slug)->firstOrFail();

        if (!$institute->allow_student_self_registration) {
            abort(403, 'This institute does not allow self-registration.');
        }

        return view('student.register', compact('institute'));
    }

    public function store(Request $request, $slug)
    {
        $institute = Institute::where('slug', $slug)->firstOrFail();

        if (!$institute->allow_student_self_registration) {
            abort(403, 'This institute does not allow self-registration.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'institute_id' => $institute->id,
            'role' => 'student',
        ]);

        Student::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'user_id' => $user->id,
            'institute_id' => $institute->id,
            'joined_date' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
