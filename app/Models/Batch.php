<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['name', 'subject', 'time_slot', 'is_active'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
