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

    public function sendEmail(Request $request, \App\Models\Enquiry $enquiry)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $institute = auth()->user()->institute;
        
        if (!$enquiry->email) {
            return response()->json(['success' => false, 'message' => 'Lead does not have an email address.'], 400);
        }

        try {
            \Illuminate\Support\Facades\Mail::to($enquiry->email)->send(
                new \App\Mail\EnquiryFollowUp($validated['subject'], $validated['body'], $institute)
            );

            return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }
}
