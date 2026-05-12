<?php

namespace App\Traits;

use App\Models\Institute;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToInstitute
{
    protected static function bootBelongsToInstitute()
    {
        static::addGlobalScope('institute', function (Builder $builder) {
            // Use hasUser() to avoid recursive DB loops when resolving the authenticated user
            if (auth()->hasUser() && auth()->user()) {
                if (auth()->user()->role === 'superadmin') {
                    return;
                }
                
                $table = $builder->getModel()->getTable();
                $builder->where(function($q) use ($table) {
                    $q->where($table . '.institute_id', auth()->user()->institute_id)
                      ->orWhereNull($table . '.institute_id');
                });
            }
        });

        static::creating(function ($model) {
            if (auth()->hasUser() && auth()->user() && auth()->user()->institute_id) {
                $model->institute_id = auth()->user()->institute_id;
            }
        });
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
