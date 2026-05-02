<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fees = \App\Models\Fee::with('student')->latest('payment_date')->get();
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
}
