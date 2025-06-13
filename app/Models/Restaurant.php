<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use JeroenG\Explorer\Application\Explored;
use Laravel\Scout\Searchable;

class Restaurant extends Model implements Explored
{
    use Searchable;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'menu_path',
        'latitude',
        'longitude',
        'telephone',
        'user_id',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'restaurant_tags', 'restaurant_id', 'tag_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray(): array
    {
        $this->loadMissing('tags');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'tags' => $this->tags->pluck('name')->toArray(),
        ];
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('tags');
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'name' => 'text',
            'description' => 'text',
            'tags' => 'text', // Just a list of tag names
        ];
    }
}
