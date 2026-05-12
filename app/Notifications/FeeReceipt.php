<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FeeReceipt extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $fee;

    public function __construct($fee)
    {
        $this->fee = $fee;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->fee->status);
        $paid = number_format($this->fee->paid_amount, 2);
        $due = number_format($this->fee->due_amount, 2);
        $month = $this->fee->month_year;

        $receiptUrl = $this->tenantRoute($this->fee->student->institute, 'fees/' . $this->fee->id . '/receipt');

        $message = (new MailMessage)
                    ->subject('Fee Payment Confirmation - ' . $this->fee->student->name)
                    ->greeting('Hello ' . $this->fee->student->name . ',')
                    ->line('This is to confirm that we have recorded a payment for your fees.')
                    ->line('**Payment Summary:**')
                    ->line('- Month/Session: ' . $month)
                    ->line('- Total Amount: ₹' . number_format($this->fee->total_amount, 2))
                    ->line('- Amount Paid: ₹' . $paid)
                    ->line('- Remaining Balance: ₹' . $due)
                    ->line('- Current Status: ' . $status);

        if ($this->fee->status !== 'pending') {
            $message->action('View & Download Receipt', $receiptUrl);
        }

        return $message->line('Thank you for choosing our institute!')
                       ->line('Team ' . $this->fee->student->institute->name);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Fee Payment recorded',
            'message' => 'Payment of ₹' . number_format($this->fee->paid_amount, 2) . ' received for ' . $this->fee->month_year,
            'link' => $this->tenantRoute($this->fee->student->institute, 'dashboard'),
            'type' => 'fee_payment'
        ];
    }
}
