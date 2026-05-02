<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use \App\Traits\BelongsToInstitute;
    use \App\Traits\LogsActivity;

    protected $fillable = ['title', 'content', 'type', 'expires_on'];

    protected function casts(): array
    {
        return ['expires_on' => 'date'];
    }

    public function isActive(): bool
    {
        return is_null($this->expires_on) || $this->expires_on->isFuture();
    }
}
