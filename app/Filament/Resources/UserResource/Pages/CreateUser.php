<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        // If the user is not a super admin or restaurant admin, assign them the restaurant_admin role
        if (! $user->hasAnyRole(['super_admin', 'restaurant_admin'])) {
            $user->assignRole('restaurant_admin');
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Користувача успішно створено';
    }
}
