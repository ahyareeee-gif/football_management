<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@football.com'
            ],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123')
            ]
        );

        $admin->assignRole('Super Admin');
    }
}
