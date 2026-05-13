<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = \App\Models\User::whereIn('role', ['teacher', 'accountant', 'receptionist'])
            ->where('institute_id', auth()->user()->institute_id)
            ->get();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:teacher,accountant,receptionist',
        ]);

        $institute = auth()->user()->institute;

        $staff = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
            'role' => $validated['role'],
            'institute_id' => $institute->id,
        ]);

        // Trigger Professional Welcome Email
        // Note: For now, we can use TeacherWelcome for all staff or create a generic StaffWelcome later.
        $staff->notify(new \App\Notifications\TeacherWelcome($institute));

        return redirect()->route('staff.index')->with('success', ucfirst($validated['role']) . ' onboarded successfully and welcome email sent.');
    }

    public function edit(\App\Models\User $staffMember)
    {
        if (!in_array($staffMember->role, ['teacher', 'accountant', 'receptionist']) || $staffMember->institute_id !== auth()->user()->institute_id) {
            abort(403);
        }
        return view('staff.edit', compact('staffMember'));
    }

    public function update(Request $request, \App\Models\User $staffMember)
    {
        if (!in_array($staffMember->role, ['teacher', 'accountant', 'receptionist']) || $staffMember->institute_id !== auth()->user()->institute_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$staffMember->id,
            'role' => 'required|in:teacher,accountant,receptionist',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $staffMember->name = $validated['name'];
        $staffMember->email = $validated['email'];
        $staffMember->role = $validated['role'];
        if ($validated['password']) {
            $staffMember->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        $staffMember->save();

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(\App\Models\User $staffMember)
    {
        if (!in_array($staffMember->role, ['teacher', 'accountant', 'receptionist']) || $staffMember->institute_id !== auth()->user()->institute_id) {
            abort(403);
        }
        $staffMember->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed.');
    }
}
