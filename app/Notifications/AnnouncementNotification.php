<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Announcement: ' . $this->announcement->title)
                    ->greeting('Hello,')
                    ->line('A new announcement has been posted at your institute.')
                    ->line('Title: ' . $this->announcement->title)
                    ->line($this->announcement->content)
                    ->action('View Dashboard', $this->tenantRoute($this->announcement->institute, 'dashboard'))
                    ->line('Stay updated!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Announcement',
            'message' => $this->announcement->title,
            'link' => $this->tenantRoute($this->announcement->institute, 'dashboard'),
            'type' => 'announcement'
        ];
    }
}
