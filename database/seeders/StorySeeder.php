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
        $users = \App\Models\User::all();
        $genres = \App\Models\Genre::all();

        foreach ($users as $user) {
            // Cada usuario tendrá 10 libros
            \App\Models\Story::factory()
                ->count(10)
                ->create(['user_id' => $user->id])
                ->each(function ($story) use ($genres) {
                    // Asignar entre 1 y 3 géneros aleatorios
                    $randomGenres = $genres->random(rand(1, 3))->pluck('id');
                    $story->genres()->attach($randomGenres);

                    // Cada libro tendrá entre 1 y 30 capítulos
                    $numChapters = rand(1, 30);
                    for ($i = 1; $i <= $numChapters; $i++) {
                        \App\Models\Chapter::factory()->create([
                            'story_id' => $story->id,
                            'order' => $i,
                            'title' => "Capítulo $i: " . \Illuminate\Support\Str::title(fake()->words(3, true)),
                        ]);
                    }
                });
        }
    }
}
