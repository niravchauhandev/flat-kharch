<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial Admin user.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@flat.com'],
            [
                'name'      => 'Flat Admin',
                'phone'     => '9876543210',
                'password'  => Hash::make('password'),
                'role'      => 1, // 1: Admin
                'is_active' => 1,
                'status'    => 1,
            ]
        );

        // Run Permission & Role Seeder
        $this->call(PermissionSeeder::class);
    }
}
