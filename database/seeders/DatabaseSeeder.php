<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create example users with roles
        User::factory()->create([
            'name' => 'Nathaniel',
            'email' => 'nathaniel@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'James',
            'email' => 'james@gmail.com',
            'password' => Hash::make('james123'),
            'role' => 'teacher',
        ]);

        User::factory()->create([
            'name' => 'Danelle',
            'email' => 'danelle@gmail.com',
            'password' => Hash::make('danel123'),
            'role' => 'student',
        ]);
    }
}
