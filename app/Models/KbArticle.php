<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

class KbArticle extends Model
{
    use Searchable;

    protected $fillable = [
        'kb_category_id', 'title', 'slug', 'content', 
        'is_published', 'views_count', 'is_internal_only'
    ];

    public function category()
    {
        return $this->belongsTo(KbCategory::class, 'kb_category_id');
    }

    public function feedback()
    {
        return $this->hasMany(KbArticleFeedback::class, 'kb_article_id');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
