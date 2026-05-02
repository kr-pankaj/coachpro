<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'institute_id',
        'student_name',
        'phone',
        'email',
        'course_interested',
        'status',
        'next_follow_up_date',
        'notes',
    ];

    protected $casts = [
        'next_follow_up_date' => 'date',
    ];
}
