<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTags extends Model
{
    protected $table = 'restaurant_tags';

    protected $fillable = [
        'restaurant_id',
        'tag_id',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
