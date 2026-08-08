<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@library.test'],
            [
                'name' => 'Library Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'librarian@library.test'],
            [
                'name' => 'Library Librarian',
                'password' => Hash::make('password'),
                'role' => 'librarian',
            ]
        );
    }
}