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
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        $admin->forceFill([
            'email_verified_at' => $admin->email_verified_at ?? now(),
            'status' => $admin->status ?? 'active',
        ])->save();

        $admin->assignRole('Super Admin');
    }
}
