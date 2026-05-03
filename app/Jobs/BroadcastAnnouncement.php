<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\Student;
use App\Notifications\AnnouncementNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BroadcastAnnouncement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $announcement;
    protected $instituteId;

    public function __construct(Announcement $announcement, $instituteId)
    {
        $this->announcement = $announcement;
        $this->instituteId = $instituteId;
    }

    public function handle(): void
    {
        // Security: Only fetch students belonging to this institute
        $students = Student::where('institute_id', $this->instituteId)
            ->with('user')
            ->get();

        foreach ($students as $student) {
            if ($student->user) {
                $student->user->notify(new AnnouncementNotification($this->announcement));
            }
        }
    }
}
