<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Batch::withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $batches = $query->paginate(10)->withQueryString();
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        $teachers = \App\Models\User::where('role', 'teacher')->get();
        return view('batches.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'time_slot' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
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
        $teachers = \App\Models\User::where('role', 'teacher')->get();
        return view('batches.edit', compact('batch', 'teachers'));
    }

    public function update(Request $request, \App\Models\Batch $batch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'time_slot' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
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
