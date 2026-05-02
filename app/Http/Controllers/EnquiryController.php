<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Enquiry::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('course_interested', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->orderByRaw("
            CASE status
                WHEN 'new' THEN 1
                WHEN 'contacted' THEN 2
                WHEN 'demo_scheduled' THEN 3
                WHEN 'converted' THEN 4
                WHEN 'lost' THEN 5
            END
        ")->orderBy('next_follow_up_date', 'asc')->paginate(12)->withQueryString();

        return view('enquiries.index', compact('enquiries'));
    }

    public function create()
    {
        return view('enquiries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'course_interested' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,demo_scheduled,converted,lost',
            'next_follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $enquiry = \App\Models\Enquiry::create($validated);

        // Notify Admin
        $admin = \App\Models\User::where('institute_id', auth()->user()->institute_id)
            ->where('role', 'admin')
            ->first();
            
        if ($admin) {
            $admin->notify(new \App\Notifications\NewLead($enquiry));
        }

        return redirect()->route('enquiries.index')->with('success', 'Lead added successfully.');
    }

    public function edit(\App\Models\Enquiry $enquiry)
    {
        return view('enquiries.edit', compact('enquiry'));
    }

    public function update(Request $request, \App\Models\Enquiry $enquiry)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'course_interested' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,demo_scheduled,converted,lost',
            'next_follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $enquiry->update($validated);

        if ($request->input('convert_to_student') && $validated['status'] === 'converted') {
            return redirect()->route('students.create', [
                'name' => $validated['student_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
            ])->with('success', 'Lead converted! Please finalize student details.');
        }

        return redirect()->route('enquiries.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(\App\Models\Enquiry $enquiry)
    {
        $enquiry->delete();
        return redirect()->route('enquiries.index')->with('success', 'Lead removed.');
    }
}
