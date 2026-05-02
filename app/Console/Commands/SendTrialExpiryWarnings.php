<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTrialExpiryWarnings extends Command
{
    protected $signature = 'trial:send-expiry-warnings';
    protected $description = 'Warn institutes 3 days before their trial expires';

    public function handle()
    {
        $targetDate = now()->addDays(3)->toDateString();

        $institutes = \App\Models\Institute::whereNull('subscription_active_until')
            ->orWhereDate('trial_ends_at', $targetDate)
            ->with('users')
            ->get();

        $count = 0;
        foreach ($institutes as $institute) {
            if (!$institute->trial_ends_at) continue;
            $daysLeft = now()->diffInDays($institute->trial_ends_at, false);
            if ($daysLeft !== 3) continue;

            $admin = $institute->users->firstWhere('role', 'admin');
            if ($admin?->email) {
                Mail::to($admin->email)->send(new \App\Mail\TrialExpiryMail($institute, 3));
                $count++;
            }
        }
        $this->info("Sent {$count} trial expiry warning(s).");
        return Command::SUCCESS;
    }
}
