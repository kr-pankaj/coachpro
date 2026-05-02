<?php
  
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use \App\Traits\LogsActivity;

    protected $fillable = ['fee_id', 'amount', 'payment_date', 'payment_method', 'remarks'];

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->fee->increment('paid_amount', $payment->amount);
        });

        static::deleted(function ($payment) {
            $payment->fee->decrement('paid_amount', $payment->amount);
        });
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
