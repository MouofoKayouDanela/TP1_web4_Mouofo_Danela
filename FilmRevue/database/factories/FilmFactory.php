<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Film;
use App\Models\Language;

class FilmFactory extends Factory
{
    protected $model = Film::class;

    public function definition()
    {
        return [
            'title'            => $this->faker->sentence(3),
            'release_year'     => $this->faker->year,
            'length'           => $this->faker->numberBetween(60, 180),
            'description'      => $this->faker->paragraph,
            'rating'           => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
            'language_id'      => Language::factory(), // génère un language associé
            'special_features' => $this->faker->randomElement(['Deleted Scenes', 'Behind the Scenes', 'Commentary']),
            'image'            => $this->faker->sentence(3),
            'created_at'       => now(),
        ];
    }
}
