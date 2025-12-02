<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Critic;

class CriticTest extends TestCase
{
    use RefreshDatabase;

   // https://laravel.com/docs/master/http-tests#available-assertions
    public function test_it_deletes_a_critic_successfully()
    {
        $critic = Critic::factory()->create();

        $response = $this->deleteJson("/api/critics/{$critic->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'critic deleted successfully']);

        $this->assertDatabaseMissing('critics', ['id' => $critic->id]);
    }
//https://laravel.com/docs/master/http-tests#available-assertions
    public function test_it_returns_404_if_critic_does_not_exist()
    {
        $nonExistingId = 999;

        $response = $this->deleteJson("/api/critics/{$nonExistingId}");

        $response->assertStatus(404)
                 ->assertSee('critic not found');
    }
}
