<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Critic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call([
        LanguageSeeder::class,
        FilmSeeder::class,
        ActorSeeder::class,
        ActorFilmSeeder::class
      ]);

        User::factory(10)->create()->each(function ($user) {

        Critic::factory(30)->create([
            'user_id' => $user->id
        ]);
    });
    }
}
