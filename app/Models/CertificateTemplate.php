<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'institute_id',
        'background_image',
        'title',
        'body_text',
        'authorized_signatory_name',
        'authorized_signatory_designation',
        'signature_image'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
