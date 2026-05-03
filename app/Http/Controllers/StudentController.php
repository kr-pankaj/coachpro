<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Batch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('batch');

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

        $students = $query->latest()->paginate(10)->withQueryString();
        $batches = Batch::all();

        return view('students.index', compact('students', 'batches'));
    }

    public function create()
    {
        $batches = Batch::where('is_active', true)->get();
        return view('students.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,13}$/'], 
            'parent_phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'address' => 'nullable|string',
            'batch_id' => 'required|exists:batches,id', // Recommended mandatory for students
            'joined_date' => 'nullable|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $institute = auth()->user()->institute;

            // 1. Create the User account
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)), // Random password, they will reset it
                'role' => 'student',
                'institute_id' => $institute->id,
            ]);

            // 2. Create the Student and link to user
            $studentData = $validated;
            $studentData['user_id'] = $user->id;
            $studentData['institute_id'] = $institute->id;
            
            $student = Student::create($studentData);

            // 3. Trigger Professional Welcome Email
            $user->notify(new \App\Notifications\NewStudentWelcome($institute));
        });

        return redirect()->route('students.index')->with('success', 'Student onboarded successfully. A welcome email with portal instructions has been sent.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Student $student)
    {
        $batches = Batch::all();
        return view('students.edit', compact('student', 'batches'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'parent_phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'address' => 'nullable|string',
            'batch_id' => 'nullable|exists:batches,id',
            'joined_date' => 'nullable|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $student) {
            $student->update($validated);

            if ($student->user) {
                $student->user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
            }
        });

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function generateIdCard(Student $student)
    {
        // Paper size in PDF points (1pt = 1/72 inch).
        // Card is 85.6mm x 54mm (CR80 vertical) = ~243pt x 371pt
        $pdf = Pdf::loadView('students.id_card', compact('student'))
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('defaultPaperWidth', 243)
            ->setOption('defaultPaperHeight', 371)
            ->setPaper([0, 0, 243, 371], 'portrait');

        return $pdf->download("ID_Card_{$student->id}.pdf");
    }
}
