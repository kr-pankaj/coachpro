<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = ['name', 'subject', 'time_slot'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
