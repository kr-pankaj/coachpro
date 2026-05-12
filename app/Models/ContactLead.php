<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'institute_name',
        'city', 'plan_interest', 'message', 'status', 'admin_notes',
    ];
}
