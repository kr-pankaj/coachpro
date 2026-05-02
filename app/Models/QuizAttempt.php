<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = ['quiz_id', 'student_id', 'score', 'total_marks', 'started_at', 'completed_at'];
    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime'];

    public function quiz() { return $this->belongsTo(Quiz::class); }
    public function student() { return $this->belongsTo(\App\Models\Student::class); }
    public function answers() { return $this->hasMany(StudentAnswer::class); }
}
