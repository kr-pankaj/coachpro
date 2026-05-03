<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fee;
use App\Notifications\OverdueFeeReminder;

class SendFeeReminders extends Command
{
    protected $signature = 'fees:send-reminders';
    protected $description = 'Send notifications for all overdue and pending fees';

    public function handle()
    {
        $overdueFees = Fee::whereIn('status', ['pending', 'partial'])
            ->where('due_amount', '>', 0)
            ->with(['student.user', 'student.institute'])
            ->get();

        $count = 0;
        foreach ($overdueFees as $fee) {
            $user = $fee->student?->user;
            if ($user) {
                $user->notify(new OverdueFeeReminder($fee));
                $count++;
            }
        }
        
        $this->info("Successfully dispatched {$count} fee notifications.");
        return Command::SUCCESS;
    }
}
