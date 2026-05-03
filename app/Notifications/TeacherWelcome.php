<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherWelcome extends Notification implements ShouldQueue
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
            ->subject('Welcome to ' . $this->institute->name . ' - Faculty Account Ready')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to the faculty team at ' . $this->institute->name . '.')
            ->line('Your teacher portal account has been successfully created. You can now manage your batches, mark attendance, and upload study materials.')
            ->line('**Login Portal:** ' . $loginUrl)
            ->action('Access Teacher Portal', $loginUrl)
            ->line('**Setting your password:** Please click the button above and use the "Forgot Password" link on the login page to set your secure password for the first time.')
            ->line('We are glad to have you with us!')
            ->line('Best Regards,')
            ->line('Administration, ' . $this->institute->name);
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Welcome to ' . $this->institute->name,
            'message' => 'Your teacher account is ready. Use forgot password to set your credentials.',
            'type' => 'welcome',
            'link' => '/dashboard'
        ];
    }
}
