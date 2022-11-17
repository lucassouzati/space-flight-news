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
        'newSite',
        'summary',
        'publishedAt',
    ];

    public function launches(): BelongsToMany
    {
        return $this->belongsToMany('App\Model\Event', 'articles_launches');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany('App\Model\Event', 'articles_events');
    }

}
