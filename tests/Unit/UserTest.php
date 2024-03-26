<?php

namespace Tests\Unit;



use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsAllUsers()
    {
        $users = User::factory()->count(10)->create();

        $response = $this->get('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(10)
            ->assertJson($users->toArray());
    }

    public function testStoreCreatesNewUser()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
        ];

        $response = $this->post('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User created successfully']);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function testShowDisplaysUser()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson($user->toArray());
    }

    public function testUpdateUserData()
    {
        $user = User::factory()->create();
        $updatedData = ['name' => 'Updated Name'];

        $response = $this->put("/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'User updated successfully']);
        $this->assertDatabaseHas('users', ['name' => 'Updated Name']);
    }

    public function testDeletesUser()
    {
        $user = User::factory()->create();

        $response = $this->delete("/api/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
