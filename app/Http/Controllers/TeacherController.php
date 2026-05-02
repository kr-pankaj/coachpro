<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = \App\Models\User::where('role', 'teacher')
            ->where('institute_id', auth()->user()->institute_id)
            ->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'teacher',
            'institute_id' => auth()->user()->institute_id,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit(\App\Models\User $teacher)
    {
        if ($teacher->role !== 'teacher' || $teacher->institute_id !== auth()->user()->institute_id) abort(403);
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, \App\Models\User $teacher)
    {
        if ($teacher->role !== 'teacher' || $teacher->institute_id !== auth()->user()->institute_id) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$teacher->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $teacher->name = $validated['name'];
        $teacher->email = $validated['email'];
        if ($validated['password']) {
            $teacher->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        $teacher->save();

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(\App\Models\User $teacher)
    {
        if ($teacher->role !== 'teacher' || $teacher->institute_id !== auth()->user()->institute_id) abort(403);
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher removed.');
    }
}
