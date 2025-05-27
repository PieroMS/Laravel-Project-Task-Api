<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Piero',
            'email' => 'piero@prueba.com',
            'country' => 'Peru',
            'age' => 23,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ])->assignRole('superadmin');

        User::factory()->create([
            'name' => 'Emma',
            'email' => 'emma@prueba.com',
            'country' => 'Reino Unido',
            'age' => 34,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ])->assignRole('admin');

        User::factory(4)->create()->each(function ($user) {
            $user->assignRole('client');
        });   
    }
}
