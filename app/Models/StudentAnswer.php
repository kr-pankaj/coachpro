<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = ['quiz_attempt_id', 'question_id', 'quiz_option_id'];

    public function attempt() { return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id'); }
    public function question() { return $this->belongsTo(Question::class); }
    public function selectedOption() { return $this->belongsTo(QuizOption::class, 'quiz_option_id'); }
}
