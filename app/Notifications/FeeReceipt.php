<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FeeReceipt extends Notification
{
    use Queueable;

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
        return (new MailMessage)
                    ->subject('Fee Receipt: ₹' . number_format($this->fee->total_amount, 2))
                    ->greeting('Hi ' . $this->fee->student->name . ',')
                    ->line('We have successfully received your fee payment.')
                    ->line('Total Course Fee: ₹' . number_format($this->fee->total_amount, 2))
                    ->line('Total Paid: ₹' . number_format($this->fee->paid_amount, 2))
                    ->line('For: ' . $this->fee->month_year)
                    ->line('Status: Paid')
                    ->action('Download Receipt', route('fees.receipt', $this->fee))
                    ->line('Thank you for your timely payment!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Fee Payment Received',
            'message' => 'Your payment of ₹' . number_format($this->fee->total_amount, 2) . ' for ' . $this->fee->month_year . ' has been recorded.',
            'link' => route('fees.index'),
            'type' => 'fee_payment'
        ];
    }
}
