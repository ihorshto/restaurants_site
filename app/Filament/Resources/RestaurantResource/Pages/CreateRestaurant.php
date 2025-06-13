<?php

namespace App\Filament\Resources\RestaurantResource\Pages;

use App\Filament\Resources\RestaurantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Якщо restaurant_admin створює ресторан, автоматично присвоюємо його ID
        if (auth()->user()->hasRole('restaurant_admin') && empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
}
