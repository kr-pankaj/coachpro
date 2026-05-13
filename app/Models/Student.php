<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'parent_phone',
        'date_of_birth', 'gender', 'school_college', 'standard_class',
        'address', 'batch_id', 'joined_date', 'user_id',
        'photo_url', 'notes', 'status',
        'institute_id',
    ];

    /**
     * Many-to-many: a student can be in multiple batches.
     */
    public function batches()
    {
        return $this->belongsToMany(Batch::class)
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    /**
     * Legacy single-batch relation (kept for backward compat).
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Returns the first active enrolled batch, falls back to legacy batch_id.
     */
    public function primaryBatch()
    {
        return $this->batches()->where('is_active', true)->first()
            ?? $this->batch;
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

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}
