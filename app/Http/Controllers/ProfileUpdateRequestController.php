<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfileUpdateRequest;
use App\Models\Student;

class ProfileUpdateRequestController extends Controller
{
    public function index()
    {
        // Admin views pending requests
        $requests = ProfileUpdateRequest::with('student')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile_requests.index', compact('requests'));
    }

    public function store(Request $request)
    {
        // Student creates a request
        $student = Student::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $changes = array_filter($request->only(['phone', 'parent_phone', 'address']));

        if (empty($changes)) {
            return back()->with('error', 'No changes submitted.');
        }

        ProfileUpdateRequest::create([
            'student_id' => $student->id,
            'requested_changes' => $changes,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Profile update requested successfully. It will be reviewed by your institute.');
    }

    public function update(Request $request, ProfileUpdateRequest $profileRequest)
    {
        // Admin approves or rejects
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $student = $profileRequest->student;
            $student->update($profileRequest->requested_changes);
            $profileRequest->status = 'approved';
        } else {
            $profileRequest->status = 'rejected';
        }

        $profileRequest->save();

        return back()->with('success', 'Request ' . $profileRequest->status . ' successfully.');
    }
}
