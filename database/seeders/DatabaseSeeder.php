<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Roles
        $adminRole = Role::create(['name' => 'master_admin', 'description' => 'Main Admin']);
        $secondaryRole = Role::create(['name' => 'secondary_admin', 'description' => 'Secondary Admin']);
        $userRole = Role::create(['name' => 'user', 'description' => 'Regular User']);
        $cpRole = Role::create(['name' => 'channel_partner', 'description' => 'Channel Partner']);

        // Main Admin
        User::create([
            'name' => 'Main Admin',
            'email' => 'admin@stablesolardekho.com',
            'password' => Hash::make('Admin@1234'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }
}
