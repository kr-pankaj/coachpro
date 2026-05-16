<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 
        'color', 'requirement_type', 'requirement_value', 'is_secret'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('earned_at')->withTimestamps();
    }
}
