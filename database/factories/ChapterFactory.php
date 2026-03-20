<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(rand(2, 5)),
            'content' => $this->faker->paragraphs(rand(5, 15), true), // Genera entre 5 y 15 párrafos
            'order' => 1, // Se ajustará en el seeder
            'story_id' => Story::factory(),
        ];
    }
}
