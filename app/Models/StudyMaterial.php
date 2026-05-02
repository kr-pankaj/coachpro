<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'institute_id', 'batch_id', 'title', 'description', 'file_url', 'type'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
