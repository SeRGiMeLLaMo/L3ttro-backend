<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Dev User',    'username' => 'devuser',  'email' => 'dev@test.com',     'rol' => 'admin'],
            ['name' => 'Ana García',  'username' => 'anagarcia','email' => 'ana@test.com',      'rol' => 'user'],
            ['name' => 'Luis Pérez',  'username' => 'luisperez','email' => 'luis@test.com',     'rol' => 'user'],
            ['name' => 'Marta Ruiz',  'username' => 'martaruiz','email' => 'marta@test.com',    'rol' => 'user'],
            ['name' => 'Carlos Mora', 'username' => 'carlosmora','email' => 'carlos@test.com',  'rol' => 'user'],
            ['name' => 'Sara López',  'username' => 'saralopez','email' => 'sara@test.com',     'rol' => 'user'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password'    => Hash::make('password'),
                    'description' => 'Usuario de prueba',
                    'photo'       => null,
                    'follows'     => 0,
                    'stories'     => false,
                    'follow_button' => true,
                ])
            );
        }
    }
}
