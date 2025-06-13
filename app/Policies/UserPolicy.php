<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'restaurant_admin']);
    }

    public function view(User $user, User $model): bool
    {
        // super_admin може переглядати всіх, restaurant_admin тільки себе
        return $user->hasRole('super_admin') || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, User $model): bool
    {
        // super_admin може редагувати всіх, restaurant_admin тільки себе
        return $user->hasRole('super_admin') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        // super_admin може видаляти інших (але не себе)
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    // Додаткові методи для специфічних дій
    public function assignRoles(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function resetPassword(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }
}
