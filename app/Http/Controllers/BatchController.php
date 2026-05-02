<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = \App\Models\Batch::withCount('students')->get();
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        return view('batches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'time_slot' => 'nullable|string|max:255'
        ]);
        \App\Models\Batch::create($validated);
        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\Batch $batch)
    {
        return view('batches.edit', compact('batch'));
    }

    public function update(Request $request, \App\Models\Batch $batch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'time_slot' => 'nullable|string|max:255'
        ]);
        $batch->update($validated);
        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    public function destroy(\App\Models\Batch $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}
