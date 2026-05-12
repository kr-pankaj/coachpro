<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class AttendanceMarked extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $attendance;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $date = Carbon::parse($this->attendance->date);
        $status = ucfirst($this->attendance->status);
        
        $subject = 'Attendance Update: ' . $date->format('M d, Y');
        if (strtolower($this->attendance->status) === 'absent') {
            $subject = '⚠️ URGENT: Absence Alert - ' . $date->format('M d, Y');
        }

        $dashboardUrl = $this->tenantRoute($this->attendance->student->institute, 'dashboard');
        
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('This is an automated attendance update for ' . $date->format('l, M d, Y') . '.')
                    ->line('Current Status: **' . $status . '**')
                    ->action('View Attendance History', $dashboardUrl)
                    ->line('If you believe this is an error, please contact the institute office immediately.')
                    ->line('Thank you,')
                    ->line('Team ' . config('app.name'));
    }

    public function toArray($notifiable)
    {
        $date = Carbon::parse($this->attendance->date);
        
        return [
            'title' => 'Attendance Marked',
            'message' => 'You were marked ' . $this->attendance->status . ' for ' . $date->format('M d') . '.',
            'link' => $this->tenantRoute($this->attendance->student->institute, 'dashboard'),
            'type' => 'attendance'
        ];
    }
}
