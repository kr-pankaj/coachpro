<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquiryThankYou extends Notification implements ShouldQueue
{
    use Queueable;

    protected $enquiry;
    protected $institute;

    public function __construct($enquiry, $institute)
    {
        $this->enquiry = $enquiry;
        $this->institute = $institute;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Thank you for your interest in ' . $this->institute->name)
            ->greeting('Hello ' . $this->enquiry->student_name . '!')
            ->line('Thank you for reaching out to us regarding our ' . ($this->enquiry->course_interested ?? 'courses') . '.')
            ->line('We have received your inquiry and our academic counselor will contact you shortly on ' . $this->enquiry->phone . ' to provide more details and answer any questions you may have.')
            ->line('In the meantime, feel free to visit our website or visit us in person.')
            ->line('**Your Inquiry Details:**')
            ->line('- Student Name: ' . $this->enquiry->student_name)
            ->line('- Interested Course: ' . ($this->enquiry->course_interested ?? 'General Inquiry'))
            ->line('We look forward to helping you achieve your academic goals!')
            ->line('Best Regards,')
            ->line('Team ' . $this->institute->name);
    }
}
