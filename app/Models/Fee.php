<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'student_id', 
        'total_amount', 
        'paid_amount', 
        'due_amount', 
        'discount_amount', 
        'payment_date', 
        'month_year', 
        'status', 
        'remarks', 
        'share_token'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($fee) {
            $fee->share_token = (string) \Illuminate\Support\Str::uuid();
            $fee->due_amount = $fee->total_amount - $fee->paid_amount - $fee->discount_amount;
            if ($fee->due_amount <= 0) {
                $fee->status = 'paid';
            } elseif ($fee->paid_amount > 0) {
                $fee->status = 'partial';
            } else {
                $fee->status = 'pending';
            }
        });

        static::updating(function ($fee) {
            $fee->due_amount = $fee->total_amount - $fee->paid_amount - $fee->discount_amount;
            if ($fee->due_amount <= 0) {
                $fee->status = 'paid';
            } elseif ($fee->paid_amount > 0) {
                $fee->status = 'partial';
            } else {
                $fee->status = 'pending';
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function isFullyPaid()
    {
        return $this->status === 'paid' || $this->due_amount <= 0;
    }
}
