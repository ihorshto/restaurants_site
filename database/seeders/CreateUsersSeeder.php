<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $restaurantAdminRole = Role::where('name', 'restaurant_admin')->first();

        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'ihor@admin.com'],
            [
                'name' => 'Ihor Super Admin',
                'email' => 'ihor@admin.com',
                'password' => Hash::make('password'),
            ]
        );

        // Create Restaurant Admin User
        $restaurantAdmin = User::firstOrCreate(
            ['email' => 'ihor@restaurant.com'],
            [
                'name' => 'Ihor Restaurant Admin',
                'email' => 'ihor@restaurant.com',
                'password' => Hash::make('password'),
            ]
        );

        // Assign roles
        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
        } else {
            $this->command->warn('Avertissement: rôle super_admin introuvable. Assurez-vous d\'exécuter d\'abord RoleSeeder.');
        }

        if ($restaurantAdminRole) {
            $restaurantAdmin->assignRole($restaurantAdminRole);
        } else {
            $this->command->warn('Avertissement: rôle restaurant_admin introuvable. Assurez-vous d\'exécuter d\'abord RoleSeeder.');
        }
    }
}
