<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewQuizAvailable extends Notification implements ShouldQueue
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
                    ->greeting('Hi ' . $notifiable->name . ',')
                    ->line('A new online quiz has been assigned to your batch.')
                    ->line('Title: ' . $this->quiz->title)
                    ->line('Time Limit: ' . $this->quiz->time_limit_minutes . ' minutes')
                    ->action('Start Quiz', route('student.quizzes.index'))
                    ->line('Good luck with your test!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Quiz Assigned',
            'message' => 'The quiz "' . $this->quiz->title . '" is now available for you to take.',
            'link' => route('student.quizzes.index'),
            'type' => 'new_quiz'
        ];
    }
}
