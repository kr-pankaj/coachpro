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
        // Fetch all pending/partial fees with student and institute context
        $pendingFees = Fee::whereIn('status', ['pending', 'partial'])
            ->where('due_amount', '>', 0)
            ->with(['student.user', 'student.institute'])
            ->get();

        $automatedCount = 0;
        $overdueCount = 0;

        foreach ($pendingFees as $fee) {
            $user = $fee->student?->user;
            $institute = $fee->student?->institute;
            if (!$user || !$institute) continue;

            // 1. Premium Feature: Automated Alerts 3 days before due date
            if ($institute->isPremium()) {
                if ($fee->payment_date && $fee->payment_date->isSameDay(now()->addDays(3))) {
                    $user->notify(new \App\Notifications\FeeReminder($fee));
                    $automatedCount++;
                    continue; // Skip overdue check if we just sent a reminder
                }
            }

            // 2. Standard Feature: Overdue Reminders (manually triggered or system-wide)
            // Note: We'll keep the system-wide overdue check as a core stability feature
            if ($fee->payment_date && $fee->payment_date->isPast() && !$fee->payment_date->isToday()) {
                $user->notify(new OverdueFeeReminder($fee));
                $overdueCount++;
            }
        }
        
        $this->info("Automated Reminders (Premium): {$automatedCount}");
        $this->info("Overdue Reminders: {$overdueCount}");
        
        return Command::SUCCESS;
    }
}
