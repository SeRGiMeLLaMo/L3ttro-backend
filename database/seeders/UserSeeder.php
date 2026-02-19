<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Dev User',
            'username' => 'devuser',
            'email' => 'dev@test.com',
            'password' => Hash::make('password'),
            'description' => 'Usuario de desarrollo',
            'rol' => 'admin',
            'photo' => null,
    ]);

     // Usuarios aleatorios usando factory
        User::factory()->count(5)->create();
    }
}
