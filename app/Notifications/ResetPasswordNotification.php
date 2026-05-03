<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $institute = $notifiable->institute;
        $baseUrl = config('app.url');
        
        // If user belongs to an institute, use their subdomain
        if ($institute) {
            $resetUrl = $this->tenantRoute($institute, 'reset-password/' . $this->token . '?email=' . urlencode($notifiable->email));
        } else {
            // Fallback for Super Admins on main domain
            $resetUrl = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->email,
            ], false));
        }

        return (new MailMessage)
            ->subject($institute ? $institute->name . ': Reset Your Password' : 'Reset Your Password')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.')
            ->line('Regards,')
            ->line($institute ? $institute->name : config('app.name'));
    }
}
