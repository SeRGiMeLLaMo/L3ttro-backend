<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\User;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $users  = User::all();
        $genres = Genre::all();

        $stories = [
            ['title' => 'El último horizonte',    'synopsis' => 'Una aventura épica al fin del mundo.'],
            ['title' => 'Sombras del pasado',      'synopsis' => 'Un detective descubre secretos oscuros.'],
            ['title' => 'La ciudad de cristal',    'synopsis' => 'Un mundo futurista lleno de peligros.'],
            ['title' => 'Entre líneas',            'synopsis' => 'El amor y la literatura se entrelazan.'],
            ['title' => 'El bosque eterno',        'synopsis' => 'Una joven entra a un bosque mágico.'],
            ['title' => 'Voces del abismo',        'synopsis' => 'Un explorador escucha voces inexplicables.'],
            ['title' => 'La herencia olvidada',    'synopsis' => 'Una familia guarda un secreto centenario.'],
            ['title' => 'Destino incierto',        'synopsis' => 'Dos extraños con un pasado en común.'],
            ['title' => 'El código roto',          'synopsis' => 'Un hacker descubre una conspiración global.'],
            ['title' => 'Más allá del tiempo',     'synopsis' => 'Un viajero regresa al pasado para salvar el futuro.'],
        ];

        foreach ($users as $user) {
            foreach ($stories as $storyData) {
                $story = Story::create([
                    'title'    => $storyData['title'],
                    'synopsis' => $storyData['synopsis'],
                    'user_id'  => $user->id,
                    'cover'    => null,
                ]);

                // Asignar géneros aleatorios
                if ($genres->count() > 0) {
                    $story->genres()->attach(
                        $genres->random(min(2, $genres->count()))->pluck('id')
                    );
                }

                // Crear 3 capítulos por historia
                for ($i = 1; $i <= 3; $i++) {
                    Chapter::create([
                        'story_id' => $story->id,
                        'order'    => $i,
                        'title'    => "Capítulo $i",
                        'content'  => "Contenido del capítulo $i de {$storyData['title']}.",
                    ]);
                }
            }
        }
    }
}
