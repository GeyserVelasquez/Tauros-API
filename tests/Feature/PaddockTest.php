<?php

namespace Tests\Feature;

use App\Models\Paddock;
use Tests\TestCase;

class PaddockTest extends TestCase
{
    public function test_users_can_get_a_list_of_paddocks(): void
    {
        Paddock::factory(3)->create();

        $route = route('paddocks.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'code',
                    'area'
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_paddock(): void
    {
        $paddock = Paddock::factory()->create();

        $route = route('paddocks.show', $paddock);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $paddock->name,
            'code' => $paddock->code,
            'area' => (string) $paddock->area
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'code',
                'area'
            ]
        ]);
    }

    public function test_users_can_create_a_new_paddock(): void
    {
        $payload = Paddock::factory()->raw();

        $route = route('paddocks.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('paddocks', [
            'name' => $payload['name']
        ]);
    }

    public function test_users_cannot_create_a_new_paddock_with_missing_parameters(): void
    {
        $payload = ['code' => 'PAD-99'];

        $route = route('paddocks.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_paddock(): void
    {
        $paddock = Paddock::factory()->create();

        $payload = [
            'name' => 'New Paddock Name',
            'area' => 15.5
        ];

        $route = route('paddocks.update', $paddock);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('paddocks', [
            'id' => $paddock->id,
            'name' => 'New Paddock Name',
            'area' => 15.5
        ]);
    }

    public function test_users_can_delete_a_paddock(): void
    {
        $paddock = Paddock::factory()->create();

        $route = route('paddocks.destroy', $paddock);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($paddock);
    }
}
