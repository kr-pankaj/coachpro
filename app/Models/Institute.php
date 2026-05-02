<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    protected $fillable = [
        'name', 'slug',
        'phone', 'contact_email', 'website', 'description',
        'address', 'city', 'state', 'pincode',
        'logo_url', 'brand_color', 'established_year',
        'allow_student_self_registration',
        'is_lifetime_free',
        'razorpay_customer_id',
        'razorpay_subscription_id',
    ];

    /** Returns a 0–100 profile completion percentage */
    public function profileCompletion(): int
    {
        $fields = ['phone','contact_email','description','address','city','state','pincode','logo_url'];
        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round(($filled / count($fields)) * 100);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(\App\Models\Student::class);
    }

    public function announcements()
    {
        return $this->hasMany(\App\Models\Announcement::class)->orderByDesc('created_at');
    }
}
