<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'type'       => 'required|in:info,warning,success',
            'expires_on' => 'nullable|date|after:today',
        ]);

        $announcement = Announcement::create($request->only('title', 'content', 'type', 'expires_on'));

        // Notify all students in the institute
        $students = \App\Models\Student::with('user')->get();
        foreach ($students as $student) {
            if ($student->user) {
                $student->user->notify(new \App\Notifications\AnnouncementNotification($announcement));
            }
        }

        return back()->with('success', 'Announcement posted!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
