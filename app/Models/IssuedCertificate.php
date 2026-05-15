<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuedCertificate extends Model
{
    protected $fillable = [
        'certificate_number',
        'institute_id',
        'student_id',
        'certificate_template_id',
        'issued_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }
}
