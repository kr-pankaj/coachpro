<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceTransaction extends Model
{
    protected $fillable = [
        'student_id', 'amount', 'reason', 
        'reference_type', 'reference_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
