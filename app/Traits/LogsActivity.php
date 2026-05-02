<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity($model, 'Created');
        });

        static::updated(function ($model) {
            static::logActivity($model, 'Updated');
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'Deleted');
        });
    }

    protected static function logActivity($model, $action)
    {
        if (auth()->check()) {
            ActivityLog::create([
                'user_id'      => auth()->id(),
                'institute_id' => auth()->user()->institute_id,
                'action'       => $action,
                'description'  => $action . ' ' . class_basename($model) . ' (ID: ' . $model->id . ')',
                'model_type'   => get_class($model),
                'model_id'     => $model->id,
                'ip_address'   => request()->ip(),
            ]);
        }
    }
}
