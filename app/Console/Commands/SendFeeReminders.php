<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFeeReminders extends Command
{
    protected $signature = 'fees:send-reminders';
    protected $description = 'Send email reminders for all pending fees';

    public function handle()
    {
        $pendingFees = \App\Models\Fee::where('status', 'pending')
            ->with('student.user', 'student.institute')
            ->get();

        $count = 0;
        foreach ($pendingFees as $fee) {
            $email = $fee->student?->user?->email ?? $fee->student?->email;
            if ($email) {
                Mail::to($email)->send(new \App\Mail\FeeReminderMail($fee));
                $count++;
            }
        }
        $this->info("Sent {$count} fee reminder(s).");
        return Command::SUCCESS;
    }
}
