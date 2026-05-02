<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = ['quiz_id', 'question_text', 'marks', 'order'];

    public function quiz() { return $this->belongsTo(Quiz::class); }
    public function options() { return $this->hasMany(QuizOption::class, 'question_id'); }
    public function correctOption() { return $this->hasOne(QuizOption::class, 'question_id')->where('is_correct', true); }
}
