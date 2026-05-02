<?php

namespace App\Traits;

use App\Models\Institute;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToInstitute
{
    protected static function bootBelongsToInstitute()
    {
        static::addGlobalScope('institute', function (Builder $builder) {
            if (auth()->check()) {
                if (auth()->user()->role === 'superadmin') {
                    return;
                }
                
                $builder->where(function($q) {
                    $q->where('institute_id', auth()->user()->institute_id)
                      ->orWhereNull('institute_id');
                });
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->institute_id) {
                $model->institute_id = auth()->user()->institute_id;
            }
        });
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
