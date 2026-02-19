<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar el seeder de géneros
        $this->call([
            GenreSeeder::class,
        ]);

        // Crear usuarios de prueba
        $this->call([
            UserSeeder::class,
        ]);


    }
}
