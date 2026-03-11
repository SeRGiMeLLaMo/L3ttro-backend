<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar 15 historias utilizando los usuarios existentes
        \App\Models\Story::factory()
            ->count(15)
            ->create()
            ->each(function ($story) {
                // Asignar entre 1 y 3 géneros aleatorios
                $genres = \App\Models\Genre::all()->random(rand(1, 3))->pluck('id');
                $story->genres()->attach($genres);
            });
    }
}
