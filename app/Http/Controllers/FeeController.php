<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Fee::with('student');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month_year')) {
            $query->where('month_year', $request->month_year);
        }

        $fees = $query->latest('payment_date')->get();
        return view('fees.index', compact('fees'));
    }

    public function create()
    {
        $students = \App\Models\Student::all();
        return view('fees.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'month_year' => 'required|string|max:7', // e.g. 2026-05
            'status' => 'required|string|in:paid,pending',
        ]);
        \App\Models\Fee::create($validated);
        return redirect()->route('fees.index')->with('success', 'Fee record created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\Fee $fee)
    {
        $students = \App\Models\Student::all();
        return view('fees.edit', compact('fee', 'students'));
    }

    public function update(Request $request, \App\Models\Fee $fee)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'month_year' => 'required|string|max:7',
            'status' => 'required|string|in:paid,pending',
        ]);
        $fee->update($validated);
        return redirect()->route('fees.index')->with('success', 'Fee record updated successfully.');
    }

    public function destroy(\App\Models\Fee $fee)
    {
        $fee->delete();
        return redirect()->route('fees.index')->with('success', 'Fee record deleted successfully.');
    }

    public function receipt(\App\Models\Fee $fee)
    {
        // Ensure only Paid fees can have receipts
        if ($fee->status !== 'paid') {
            abort(403, 'Receipts are only available for paid fees.');
        }

        // Security check
        $user = auth()->user();
        if ($user->role === 'student') {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if (!$student || $student->id !== $fee->student_id) {
                abort(403, 'Unauthorized action.');
            }
        } elseif ($user->role === 'admin') {
            if ($user->institute_id !== $fee->student->institute_id) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        $institute = $fee->student->institute;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('fees.receipt', compact('fee', 'institute'));
        
        return $pdf->download('Receipt-' . str_pad($fee->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
