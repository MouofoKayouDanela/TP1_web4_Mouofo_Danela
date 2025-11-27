<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Film;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Critic>
 */
class CriticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'film_id' => Film::inRandomOrder()->first()?->id, 
            'score' => $this->faker->randomFloat(1, 0, 9.9),
            'comment'  => $this->faker->text(),
            'created_at' => now(),
            'updated_at' => null,
        ];
    }
}
