<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'code',
        'title',
        'type',
        'genre',
        'rating',
        'synopsis',
        'is_available',
        'total_episodes',
        'studio',
        'resolution',
        'has_sub_indo',
        'total_chapters',
        'author',
        'is_colored',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'is_available' => 'boolean',
            'total_episodes' => 'integer',
            'has_sub_indo' => 'boolean',
            'total_chapters' => 'integer',
            'is_colored' => 'boolean',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Users who have added this media to their collection.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user')
            ->withPivot('progress')
            ->withTimestamps();
    }
}
