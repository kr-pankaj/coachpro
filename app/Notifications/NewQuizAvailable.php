<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewQuizAvailable extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasTenantUrl;

    protected $quiz;

    public function __construct($quiz)
    {
        $this->quiz = $quiz;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Quiz Assigned: ' . $this->quiz->title)
                    ->greeting('Hi ' . $notifiable->name . ',')
                    ->line('A new online quiz has been assigned to your batch.')
                    ->line('Title: ' . $this->quiz->title)
                    ->line('Time Limit: ' . $this->quiz->time_limit_minutes . ' minutes')
                    ->action('Start Quiz', $this->tenantRoute($this->quiz->batch->institute, 'quizzes'))
                    ->line('Good luck with your test!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Quiz Assigned',
            'message' => 'The quiz "' . $this->quiz->title . '" is now available for you to take.',
            'link' => $this->tenantRoute($this->quiz->batch->institute, 'dashboard'),
            'type' => 'new_quiz'
        ];
    }
}
