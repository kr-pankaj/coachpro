<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeInstituteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Institute $institute, public string $adminName) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to QuonixAI – Your 14-Day Trial Has Started!');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.welcome-institute', with: [
            'instituteName' => $this->institute->name,
            'adminName' => $this->adminName,
        ]);
    }
}
