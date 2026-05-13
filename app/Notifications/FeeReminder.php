<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeeReminder extends Notification
{
    use Queueable;

    protected $fee;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Fee $fee)
    {
        $this->fee = $fee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $studentName = $this->fee->student->student_name;
        $instituteName = $this->fee->student->institute->name;
        $amount = $this->fee->due_amount;
        $month = $this->fee->month_year;

        return (new MailMessage)
            ->subject("Upcoming Fee Reminder - {$instituteName}")
            ->greeting("Hello {$studentName},")
            ->line("This is a friendly reminder regarding your upcoming fee for **{$month}**.")
            ->line("Total Due: **₹" . number_format($amount, 2) . "**")
            ->line("Please ensure the payment is made by the due date to avoid any late charges or interruptions.")
            ->action('View & Pay Fees', url("/fees/{$this->fee->id}"))
            ->line("If you have already paid, please ignore this message.")
            ->salutation("Best regards, \n{$instituteName}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'fee_id' => $this->fee->id,
            'amount' => $this->fee->due_amount,
            'month' => $this->fee->month_year,
            'message' => "Fee reminder for {$this->fee->month_year}: ₹" . number_format($this->fee->due_amount, 2),
        ];
    }
}
