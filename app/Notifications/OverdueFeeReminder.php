<?php

namespace App\Notifications;

use App\Models\Fee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueFeeReminder extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $fee;

    public function __construct(Fee $fee)
    {
        $this->fee = $fee;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $institute = $this->fee->student->institute;
        $url = route('fees.share', $this->fee->share_token);

        return (new MailMessage)
            ->subject('Fee Payment Reminder - ' . $institute->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a friendly reminder that your fees for ' . $this->fee->month_year . ' are still pending.')
            ->line('Outstanding Amount: ₹' . number_format($this->fee->due_amount))
            ->action('View Receipt & Pay', $url)
            ->line('If you have already paid, please ignore this message.')
            ->line('Thank you for being a part of ' . $institute->name . '!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'fee_reminder',
            'title' => 'Fee Payment Reminder',
            'message' => 'Your fees for ' . $this->fee->month_year . ' are pending (₹' . number_format($this->fee->due_amount) . ').',
            'fee_id' => $this->fee->id,
            'link' => $this->tenantRoute($this->fee->student->institute, 'dashboard'),
        ];
    }
}
