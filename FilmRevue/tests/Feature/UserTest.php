<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

//https://laravel.com/docs/master/http-tests#available-assertions
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_user_successfully()
    {
        $payload = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'login'      => 'johndoe',
            'email'      => 'john@example.com',
            'password'   => 'password123'
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['first_name' => 'John', 'email' => 'john@example.com']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

 
    public function test_it_returns_422_when_creating_user_with_invalid_data()
    {
        $payload = [
            'first_name' => '',
            'last_name'  => '',
            'login'      => '',
            'email'      => 'not-an-email',
            'password'   => '123'
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(422);
    }

    public function test_it_updates_a_user_successfully()
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'password' => Hash::make('oldpassword')
        ]);

        $payload = [
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'login'      => 'janesmith',
            'email'      => 'jane@example.com',
            'password'   => 'newpassword123'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['first_name' => 'Jane', 'email' => 'jane@example.com']);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }


    public function test_it_returns_404_when_updating_nonexistent_user()
    {
        $payload = [
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'login'      => 'janesmith',
            'email'      => 'jane@example.com',
            'password'   => 'newpassword123'
        ];

        $response = $this->putJson("/api/users/999", $payload);

        $response->assertStatus(404)
                 ->assertSee('User not found');
    }

 
    public function test_it_returns_422_when_updating_user_with_invalid_data()
    {
        $user = User::factory()->create();

        $payload = [
            'first_name' => '',
            'last_name'  => '',
            'login'      => '',
            'email'      => 'invalid-email',
            'password'   => '123'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $payload);

        $response->assertStatus(422);
    }
}
