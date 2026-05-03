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
        $query = Student::with('batches');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('batches', fn($q) => $q->where('batches.id', $request->batch_id));
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
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone'        => ['required', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'parent_phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'address'      => 'nullable|string',
            'batch_ids'    => 'required|array|min:1',
            'batch_ids.*'  => 'exists:batches,id',
            'joined_date'  => 'nullable|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
            $institute = auth()->user()->institute;

            // 1. Create the User account
            $user = \App\Models\User::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'password'     => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                'role'         => 'student',
                'institute_id' => $institute->id,
            ]);

            // 2. Create the Student record (no batch_id here — using pivot)
            $student = Student::create([
                'name'         => $validated['name'],
                'phone'        => $validated['phone'],
                'parent_phone' => $validated['parent_phone'] ?? null,
                'address'      => $validated['address'] ?? null,
                'joined_date'  => $validated['joined_date'] ?? null,
                'user_id'      => $user->id,
                'institute_id' => $institute->id,
            ]);

            // 3. Sync batches via pivot table
            $student->batches()->sync($validated['batch_ids']);

            // 4. Send Welcome Email
            $user->notify(new \App\Notifications\NewStudentWelcome($institute));
        });

        return redirect()->route('students.index')->with('success', 'Student onboarded successfully. A welcome email with portal instructions has been sent.');
    }

    public function show(Student $student)
    {
        $student->load(['batches', 'user', 'attendances']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $batches = Batch::all();
        $enrolledBatchIds = $student->batches()->pluck('batches.id')->toArray();
        return view('students.edit', compact('student', 'batches', 'enrolledBatchIds'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $student->user_id,
            'phone'        => ['nullable', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'parent_phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'address'      => 'nullable|string',
            'batch_ids'    => 'nullable|array',
            'batch_ids.*'  => 'exists:batches,id',
            'joined_date'  => 'nullable|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $student) {
            $student->update(\Illuminate\Support\Arr::except($validated, ['batch_ids']));

            if ($student->user) {
                $student->user->update([
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                ]);
            }

            // Sync batches via pivot table
            $student->batches()->sync($validated['batch_ids'] ?? []);
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
