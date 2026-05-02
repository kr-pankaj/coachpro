<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Student::with('batch');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $students = $query->latest()->get();
        $batches = \App\Models\Batch::all();

        return view('students.index', compact('students', 'batches'));
    }

    public function create()
    {
        $batches = \App\Models\Batch::all();
        return view('students.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'batch_id' => 'nullable|exists:batches,id',
            'joined_date' => 'nullable|date',
        ]);
        \App\Models\Student::create($validated);
        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\Student $student)
    {
        $batches = \App\Models\Batch::all();
        return view('students.edit', compact('student', 'batches'));
    }

    public function update(Request $request, \App\Models\Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'batch_id' => 'nullable|exists:batches,id',
            'joined_date' => 'nullable|date',
        ]);
        $student->update($validated);
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(\App\Models\Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
