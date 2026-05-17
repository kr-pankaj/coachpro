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
        'xp_total', 'level', 'current_streak', 'longest_streak', 'last_activity_at',
        'bio', 'skills', 'notable_achievements', 'github_url', 'linkedin_url', 'show_attendance_on_portfolio', 'projects'
    ];

    protected $casts = [
        'skills' => 'array',
        'notable_achievements' => 'array',
        'projects' => 'array',
        'last_activity_at' => 'datetime',
        'show_attendance_on_portfolio' => 'boolean',
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

    public function experienceTransactions()
    {
        return $this->hasMany(ExperienceTransaction::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class)->withPivot('earned_at')->withTimestamps();
    }

    public function currentLevelXp()
    {
        return pow($this->level - 1, 2) * 100;
    }

    public function nextLevelXp()
    {
        return pow($this->level, 2) * 100;
    }

    public function levelProgress()
    {
        $currentXp = $this->xp_total;
        $min = $this->currentLevelXp();
        $max = $this->nextLevelXp();
        
        if ($max == $min) return 100;
        
        $pct = (($currentXp - $min) / ($max - $min)) * 100;
        return min(100, max(0, $pct));
    }
}
