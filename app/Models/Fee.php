<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = ['student_id', 'amount', 'payment_date', 'month_year', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
