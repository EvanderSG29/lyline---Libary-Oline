<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Example',
            'email' => 'admin@email.com',
            'role' => UserRole::Admin,
            'password' => Hash::make("123456789"),
        ]);

        User::factory()->create([
            'name' => 'Staff Example',
            'email' => 'staff@email.com',
            'role' => UserRole::Staff,
            'password' => Hash::make("123456789"),
        ]);

        User::factory()->create([
            'name' => 'User Example',
            'email' => 'user@email.com',
            'role' => UserRole::User,
            'password' => Hash::make("123456789"),
        ]);
    }
}
