<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CREATE Users
        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            // 'email_verified_at' => now(),
            'password' => Hash::make('00000000'),
            // 'remember_token' => Str::random(10),
        ])->assignRole('superadmin');

        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            // 'email_verified_at' => now(),
            'password' => Hash::make('11111111'),
            // 'remember_token' => Str::random(10),
        ])->assignRole('admin');

        User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            // 'email_verified_at' => now(),
            'password' => Hash::make('99999999'),
            // 'remember_token' => Str::random(10)
        ])->assignRole('user');
    }
}
