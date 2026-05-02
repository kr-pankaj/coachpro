<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewQuizAvailable extends Notification
{
    use Queueable;

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
                    ->greeting('Hi Student,')
                    ->line('A new online quiz has been assigned to your batch.')
                    ->line('Title: ' . $this->quiz->title)
                    ->line('Time Limit: ' . $this->quiz->time_limit_minutes . ' minutes')
                    ->action('Start Quiz', url('/student/quizzes'))
                    ->line('Good luck with your test!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Quiz Assigned',
            'message' => 'The quiz "' . $this->quiz->title . '" is now available for you to take.',
            'link' => url('/student/quizzes'),
            'type' => 'new_quiz'
        ];
    }
}
