<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'name', 'email', 'phone', 'parent_phone',
        'date_of_birth', 'gender', 'school_college', 'standard_class',
        'address', 'batch_id', 'joined_date', 'user_id',
        'photo_url', 'notes', 'status',
        'institute_id',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
