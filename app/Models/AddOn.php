<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'icon',
        'category',
        'is_active',
        'is_promoted',
        'image_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_promoted' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function institutes()
    {
        return $this->belongsToMany(Institute::class, 'institute_add_ons')
            ->withPivot('purchased_at', 'price_paid', 'razorpay_payment_id')
            ->withTimestamps();
    }
}
