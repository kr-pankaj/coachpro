<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class PortfolioSettingsController extends Controller
{
    public function edit()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        return view('student.portfolio.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        
        $validated = $request->validate([
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string', // Comma separated
            'notable_achievements' => 'nullable|string', // Comma separated
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'show_attendance_on_portfolio' => 'boolean',
            'projects' => 'nullable|array',
            'projects.*.title' => 'required|string|max:255',
            'projects.*.tech' => 'nullable|string|max:255',
            'projects.*.link' => 'nullable|url|max:255',
            'projects.*.description' => 'nullable|string|max:500',
        ]);

        // Convert comma-separated strings to arrays
        if (isset($validated['skills'])) {
            $validated['skills'] = array_map('trim', explode(',', $validated['skills']));
        }
        
        if (isset($validated['notable_achievements'])) {
            $validated['notable_achievements'] = array_map('trim', explode(',', $validated['notable_achievements']));
        }

        $student->update($validated);

        return redirect()->back()->with('success', 'Portfolio updated successfully.');
    }
}
