<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = ['institute_id', 'batch_id', 'title', 'description', 'time_limit_minutes', 'start_time', 'end_time', 'is_active'];

    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime', 'is_active' => 'boolean'];

    public function questions() { return $this->hasMany(Question::class); }
    public function batch() { return $this->belongsTo(\App\Models\Batch::class); }
    public function attempts() { return $this->hasMany(QuizAttempt::class); }
}
