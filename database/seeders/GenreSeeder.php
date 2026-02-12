<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre; // IMPORTANTE: importar el modelo

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Fantasía',
            'Ciencia ficción',
            'Romance',
            'Terror',
            'Misterio',
            'Aventura',
            'Drama',
            'Comedia',
            'Histórica',
            'Juvenil',
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre
            ]);
        }
    }
}
