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
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'email' => 'nullable|email|max:255',
            'course_interested' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,demo_scheduled,converted,lost',
            'next_follow_up_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $enquiry = \App\Models\Enquiry::create($validated);
        $institute = auth()->user()->institute;

        // 1. Notify Admin (New Lead Alert)
        $admin = \App\Models\User::where('institute_id', $institute->id)
            ->where('role', 'admin')
            ->first();
            
        if ($admin) {
            $admin->notify(new \App\Notifications\NewLead($enquiry));
        }

        // 2. Notify Inquirer (Professional Thank You)
        if ($enquiry->email) {
            \Illuminate\Support\Facades\Notification::route('mail', $enquiry->email)
                ->notify(new \App\Notifications\InquiryThankYou($enquiry, $institute));
        }

        return redirect()->route('enquiries.index')->with('success', 'Lead added successfully and thank you email sent.');
    }

    public function edit(\App\Models\Enquiry $enquiry)
    {
        return view('enquiries.edit', compact('enquiry'));
    }

    public function update(Request $request, \App\Models\Enquiry $enquiry)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,13}$/'],
            'email' => 'nullable|email|max:255',
            'course_interested' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,demo_scheduled,converted,lost',
            'next_follow_up_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
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

    public function suggestEmail(\App\Models\Enquiry $enquiry)
    {
        $institute = auth()->user()->institute;
        if (!$institute->isPremium()) {
            return response()->json(['error' => 'Premium subscription required'], 403);
        }

        $course = $enquiry->course_interested ?? 'our courses';
        $firstName = explode(' ', $enquiry->student_name)[0];

        $subject = "Quick update: Your interest in {$course} at {$institute->name}";
        $body = "Hi {$firstName},\n\n" .
                "Thank you for reaching out to {$institute->name}. We noticed you were interested in our {$course} program and wanted to provide a bit more information.\n\n" .
                "Our next batch is scheduled to begin shortly, and we would love to invite you for a complimentary demo session to experience our teaching methodology firsthand.\n\n" .
                "Do you have a few minutes this week for a quick call to discuss your goals?\n\n" .
                "Best regards,\n" .
                "{$institute->name} Team\n" .
                "{$institute->phone}";

        return response()->json([
            'subject' => $subject,
            'body' => $body,
            'email' => $enquiry->email
        ]);
    }
}
