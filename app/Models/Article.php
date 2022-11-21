<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'featured',
        'title',
        'url',
        'imageUrl',
        'newsSite',
        'summary',
        'publishedAt',
    ];

    public function launches(): BelongsToMany
    {
        return $this->belongsToMany(Launch::class, 'articles_launches');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'articles_events');
    }

}
