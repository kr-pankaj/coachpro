<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = [
        'institute_id', 'razorpay_order_id', 'razorpay_payment_id',
        'amount', 'months', 'plan_name', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
