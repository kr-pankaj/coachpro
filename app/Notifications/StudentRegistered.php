<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StudentRegistered extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Student Registration: ' . $this->student->name)
                    ->greeting('Hello Admin,')
                    ->line('A new student has registered at your institute.')
                    ->line('Name: ' . $this->student->name)
                    ->line('Email: ' . $this->student->email)
                    ->line('Phone: ' . ($this->student->phone ?? 'N/A'))
                    ->action('View Student Details', $this->tenantRoute($this->student->institute, 'students'))
                    ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Student Registered',
            'message' => $this->student->name . ' has just registered.',
            'link' => $this->tenantRoute($this->student->institute, 'students'),
            'type' => 'student_registration'
        ];
    }
}
