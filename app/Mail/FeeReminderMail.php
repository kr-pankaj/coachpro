<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Fee $fee) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Fee Payment Reminder – ' . $this->fee->month_year);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.fee-reminder', with: [
            'studentName' => $this->fee->student?->name,
            'instituteName' => $this->fee->student?->institute?->name,
            'monthYear' => $this->fee->month_year,
            'amount' => $this->fee->amount,
        ]);
    }
}
