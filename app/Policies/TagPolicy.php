<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'restaurant_admin']);
    }

    public function view(User $user, Tag $tag): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // restaurant_admin може переглядати тільки теги своїх ресторанів
        if ($user->hasRole('restaurant_admin')) {
            $restaurantIds = $user->restaurants()->pluck('id');

            return $tag->restaurants()->whereIn('restaurants.id', $restaurantIds)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->hasRole('super_admin');
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasRole('super_admin');
    }
}
