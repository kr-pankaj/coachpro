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

        $data = $request->only('title', 'content', 'type', 'expires_on');
        $data['institute_id'] = auth()->user()->institute_id;
        $data['is_active'] = true;

        $announcement = Announcement::create($data);

        // Dispatch background job for broadcasting to students
        \App\Jobs\BroadcastAnnouncement::dispatch($announcement, auth()->user()->institute_id);

        return back()->with('success', 'Announcement posted and broadcast started in background.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
