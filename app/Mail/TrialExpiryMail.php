<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Institute $institute, public int $daysLeft) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Your CoachPro Trial Expires in {$this->daysLeft} Days!");
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.trial-expiry', with: [
            'instituteName' => $this->institute->name,
            'daysLeft' => $this->daysLeft,
        ]);
    }
}
