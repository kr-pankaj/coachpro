<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'institute_id',
        'batch_id',
        'title',
        'category',
        'amount',
        'expense_date',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    // Category metadata
    public static array $categories = [
        'salary'      => ['label' => 'Staff Salary',  'color' => 'indigo'],
        'rent'        => ['label' => 'Rent',           'color' => 'amber'],
        'utilities'   => ['label' => 'Utilities',      'color' => 'cyan'],
        'marketing'   => ['label' => 'Marketing',      'color' => 'violet'],
        'maintenance' => ['label' => 'Maintenance',    'color' => 'rose'],
        'other'       => ['label' => 'Other',          'color' => 'gray'],
    ];

    public function getCategoryLabelAttribute(): string
    {
        return self::$categories[$this->category]['label'] ?? ucfirst($this->category);
    }

    public function getCategoryColorAttribute(): string
    {
        return self::$categories[$this->category]['color'] ?? 'gray';
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
