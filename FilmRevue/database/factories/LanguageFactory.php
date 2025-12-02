<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Language;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition()
    {
        return [
            'name' => $this->faker->languageCode, // ou $this->faker->word
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
