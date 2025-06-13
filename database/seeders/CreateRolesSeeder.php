<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateRolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'restaurant_admin']);
    }
}
