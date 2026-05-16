<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewStudentWelcome extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $institute;

    public function __construct($institute)
    {
        $this->institute = $institute;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginUrl = $this->tenantRoute($this->institute, 'login');

        return (new MailMessage)
            ->subject('Welcome to ' . $this->institute->name . ' 🎓')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to **' . $this->institute->name . '**. We are excited to support your learning journey!')
            ->line('Your student portal account is now ready. Through this portal, you can:')
            ->line('✨ Track your attendance in real-time')
            ->line('✨ Download study materials and assignments')
            ->line('✨ View fee history and download receipts')
            ->line('✨ Participate in online assessments')
            ->action('Enter Your Student Portal', $loginUrl)
            ->line('**How to set your password:**')
            ->line('For your security, we haven\'t set a default password. Please click the button above, click "Forgot Password", and enter your email (**' . $notifiable->email . '**) to set your secure access password.')
            ->line('We wish you all the best in your studies!')
            ->salutation('Best Regards,  ' . $this->institute->name);
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Welcome to ' . $this->institute->name,
            'message' => 'Your portal account has been created. Use the forgot password link to set your password.',
            'type' => 'welcome',
            'link' => $this->tenantRoute($this->institute, 'dashboard')
        ];
    }
}
