<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_list_clients(): void
    {
        Client::factory()->count(5)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_search_clients(): void
    {
        Client::factory()->create(['name' => 'John Doe']);
        Client::factory()->create(['name' => 'Jane Smith']);

        $response = $this->getJson('/api/clients?search=John');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'John Doe');
    }

    public function test_can_filter_clients_by_status(): void
    {
        Client::factory()->create(['status' => 'active']);
        Client::factory()->create(['status' => 'inactive']);

        $response = $this->getJson('/api/clients?status=active');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'active');
    }

    public function test_can_create_client(): void
    {
        $data = [
            'name' => 'Test Client',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'company' => 'Test Inc',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/clients', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', $data);
    }

    public function test_can_update_client(): void
    {
        $client = Client::factory()->create();
        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/clients/{$client->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
