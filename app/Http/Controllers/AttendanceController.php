<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date     = $request->get('date', now()->toDateString());
        $batch_id = $request->get('batch_id');
        
        // Show all active batches, PLUS the current one even if it's inactive (for history)
        $batches = \App\Models\Batch::where('is_active', true)
                    ->when($batch_id, function($q) use ($batch_id) {
                        return $q->orWhere('id', $batch_id);
                    })
                    ->get();

        $students   = collect();
        $attendances = [];
        if ($batch_id) {
            $students    = \App\Models\Student::whereHas('batches', function($q) use ($batch_id) {
                $q->where('batches.id', $batch_id);
            })->get();
            $attendances = \App\Models\Attendance::where('batch_id', $batch_id)
                            ->whereDate('date', $date)
                            ->get()
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

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            foreach ($validated['attendance'] as $student_id => $status) {
                $attendance = \App\Models\Attendance::updateOrCreate(
                    [
                        'student_id' => $student_id,
                        'batch_id' => $validated['batch_id'],
                        'date' => $validated['date'],
                        'institute_id' => auth()->user()->institute_id,
                    ],
                    ['status' => $status]
                );

                if ($attendance->wasRecentlyCreated || $attendance->wasChanged('status')) {
                    if ($attendance->student && $attendance->student->user) {
                        $attendance->student->user->notify(new \App\Notifications\AttendanceMarked($attendance));
                    }
                }
            }
        });

        return back()->with('success', 'Attendance saved successfully and notifications sent.');
    }
}
