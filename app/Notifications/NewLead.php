<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewLead extends Notification
{
    use Queueable;

    protected $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Lead: ' . $this->lead->student_name)
                    ->greeting('Hello Admin,')
                    ->line('A new prospect has inquired about a course.')
                    ->line('Name: ' . $this->lead->student_name)
                    ->line('Phone: ' . $this->lead->phone)
                    ->line('Course: ' . ($this->lead->course_interested ?? 'Not Specified'))
                    ->action('View Pipeline', route('enquiries.index'))
                    ->line('Don\'t forget to follow up by ' . ($this->lead->next_follow_up_date ? $this->lead->next_follow_up_date->format('M d') : 'ASAP') . '!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Lead Recieved',
            'message' => $this->lead->student_name . ' interested in ' . ($this->lead->course_interested ?? 'Courses'),
            'link' => route('enquiries.index'),
            'type' => 'lead'
        ];
    }
}
