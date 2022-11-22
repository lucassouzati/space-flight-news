<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'featured',
        'title',
        'url',
        'imageUrl',
        'newsSite',
        'summary',
        'publishedAt',
    ];

    public static $rules = [
        'api_id' => 'integer',
        'featured' => 'required|boolean',
        'title' => 'required|string',
        'url' => 'required|string',
        'imageUrl' => 'required|string',
        'newsSite' => 'string',
        'summary' => 'string',
        'publishedAt' => 'string',
        'launches.*.id' => 'uuid',
        'launches.*.provider' => 'string',
        'events.*.id' => 'integer',
        'events.*.provider' => 'string',
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
