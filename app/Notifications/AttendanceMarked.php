<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AttendanceMarked extends Notification
{
    use Queueable;

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
        $status = ucfirst($this->attendance->status);
        return (new MailMessage)
                    ->subject('Attendance Update: ' . $this->attendance->date->format('M d, Y'))
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your attendance has been marked for ' . $this->attendance->date->format('l, M d, Y') . '.')
                    ->line('Status: ' . $status)
                    ->action('View Attendance History', route('dashboard'))
                    ->line('Thank you for being part of ' . config('app.name') . '!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Attendance Marked',
            'message' => 'You were marked ' . $this->attendance->status . ' for ' . $this->attendance->date->format('M d') . '.',
            'link' => route('dashboard'),
            'type' => 'attendance'
        ];
    }
}
