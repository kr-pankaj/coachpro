<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity;

    protected $fillable = ['student_id', 'batch_id', 'date', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
