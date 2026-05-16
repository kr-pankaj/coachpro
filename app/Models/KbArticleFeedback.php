<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KbArticleFeedback extends Model
{
    protected $fillable = ['kb_article_id', 'is_helpful', 'user_id', 'ip_address'];

    public function article()
    {
        return $this->belongsTo(KbArticle::class, 'kb_article_id');
    }
}
