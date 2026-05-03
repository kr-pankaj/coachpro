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
            $fee = $payment->fee;
            $fee->paid_amount += $payment->amount;
            $fee->save(); // This triggers the 'updating' event in Fee model to recalculate due_amount
        });

        static::deleted(function ($payment) {
            $fee = $payment->fee;
            $fee->paid_amount -= $payment->amount;
            $fee->save();
        });
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
