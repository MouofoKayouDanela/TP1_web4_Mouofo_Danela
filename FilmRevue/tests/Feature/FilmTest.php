<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Film;
use App\Models\Actor;
use App\Models\Critic;
use App\Models\User;

use Mockery; 

//https://laravel.com/docs/master/http-tests#available-assertions
class FilmTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_index_returns_all_films()
    {
        Film::factory()->count(3)->create();

        $response = $this->getJson('/api/films');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_get_film_actors_returns_actors()
    {
        $film = Film::factory()->create();
        $actors = Actor::factory()->count(2)->create();
        $film->actors()->attach($actors->pluck('id'));

        $response = $this->getJson("/api/films/{$film->id}/actors");

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

 
    public function test_get_film_actors_returns_404_if_film_not_found()
    {
        $response = $this->getJson('/api/films/999/actors');

        $response->assertStatus(404)
                 ->assertSee('Film not found');
    }


   
    public function test_get_film_actors_handles_server_error()
    {
        $this->mock(Film::class, function ($mock) {
            $mock->shouldReceive('with')->andThrow(new \Exception);
        });

        $response = $this->getJson('/films/1/actors');

        $response->assertStatus(500)
                 ->assertSee('Server error');
    }
 
    public function test_get_film_critics_returns_film_and_critics()
    {
        $film = Film::factory()->create();$user = User::factory()->create();

        $critics = Critic::factory()
            ->count(2)
            ->create([
                'film_id' => $film->id,
                'user_id' => $user->id
            ]);

        $response = $this->getJson("/api/films/{$film->id}/critics");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'film',
                     'critics'
                 ])
                 ->assertJsonCount(2, 'critics');
    }

 
  
    public function test_get_film_critics_returns_404_if_film_not_found()
    {
        $response = $this->getJson('/api/films/999/critics');

        $response->assertStatus(404)
                 ->assertSee('Film not found');
    }


 
}
