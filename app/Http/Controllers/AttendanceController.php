<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date     = $request->get('date', now()->toDateString());
        $batch_id = $request->get('batch_id');
        $batches  = \App\Models\Batch::all();

        $students   = collect();
        $attendances = [];
        if ($batch_id) {
            $students    = \App\Models\Student::where('batch_id', $batch_id)->get();
            $attendances = \App\Models\Attendance::where('batch_id', $batch_id)
                            ->where('date', $date)
                            ->pluck('status', 'student_id')
                            ->toArray();
        }

        return view('attendances.index', compact('batches', 'students', 'attendances', 'date', 'batch_id'));
    }

    /**
     * "Create" redirects to index — attendance is marked inline with the batch/date picker.
     */
    public function create()
    {
        return redirect()->route('attendances.index')
            ->with('info', 'Select a batch and date below to mark attendance.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        foreach ($validated['attendance'] as $student_id => $status) {
            $attendance = \App\Models\Attendance::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'batch_id' => $validated['batch_id'],
                    'date' => $validated['date'],
                ],
                ['status' => $status]
            );

            // Notify Student if user account exists
            if ($attendance->wasRecentlyCreated || $attendance->wasChanged('status')) {
                if ($attendance->student && $attendance->student->user) {
                    $attendance->student->user->notify(new \App\Notifications\AttendanceMarked($attendance));
                }
            }
        }

        return back()->with('success', 'Attendance saved successfully and notifications sent.');
    }
}
