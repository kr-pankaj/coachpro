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
            ->subject('Welcome to ' . $this->institute->name . ' - Your Student Portal is Ready')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to ' . $this->institute->name . '. We are excited to have you with us!')
            ->line('Your student portal account has been successfully created. Through this portal, you can access your attendance history, fees records, study materials, and more.')
            ->line('**Login Portal:** ' . $loginUrl)
            ->action('Access Your Dashboard', $loginUrl)
            ->line('**Setting your password:** For security, we have not set a password for you. Please click the button above and use the "Forgot Password" link on the login page to set your account password for the first time.')
            ->line('If you have any questions, please contact the institute office.')
            ->line('Welcome aboard!');
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
