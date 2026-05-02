<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileUpdateRequest extends Model
{
    use \App\Traits\BelongsToInstitute;

    protected $fillable = ['student_id', 'requested_changes', 'status'];

    protected function casts(): array
    {
        return [
            'requested_changes' => 'array',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
