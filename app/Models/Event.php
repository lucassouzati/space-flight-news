<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'provider'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany('App\Models\Article', 'articles_events');
    }
}
