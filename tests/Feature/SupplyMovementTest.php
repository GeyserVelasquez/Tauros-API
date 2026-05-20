<?php

namespace Tests\Feature;

use App\Models\SupplyMovement;
use Tests\TestCase;

class SupplyMovementTest extends TestCase
{
    public function test_users_can_get_a_list_of_supply_movements(): void
    {
        SupplyMovement::factory(3)->create();

        $route = route('supply-movements.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'supply_id',
                    'type',
                    'made_at',
                    'attributes',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_supply_movement(): void
    {
        $supplyMovement = SupplyMovement::factory()->create();

        $route = route('supply-movements.show', $supplyMovement);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $supplyMovement->id,
            'supply_id' => $supplyMovement->supply_id,
            'type' => $supplyMovement->type,
            'attributes' => $supplyMovement->attributes,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'supply_id',
                'type',
                'made_at',
                'attributes',
            ]
        ]);
    }

    public function test_users_can_create_a_new_supply_movement(): void
    {
        $payload = SupplyMovement::factory()->raw();

        $payload['made_at'] = $payload['made_at']->format('Y-m-d H:i:s');

        $route = route('supply-movements.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('supply_movements', [
            'supply_id' => $payload['supply_id'],
            'type' => $payload['type'],
        ]);
    }

    public function test_users_cannot_create_a_new_supply_movement_with_missing_parameters(): void
    {
        $payload = ['attributes' => ['foo' => 'bar']];

        $route = route('supply-movements.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_supply_movement(): void
    {
        $supplyMovement = SupplyMovement::factory()->create();

        $payload = [
            'type' => 'loss'
        ];

        $route = route('supply-movements.update', $supplyMovement);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('supply_movements', [
            'id' => $supplyMovement->id,
            'type' => $payload['type']
        ]);
    }

    public function test_users_can_delete_a_supply_movement(): void
    {
        $supplyMovement = SupplyMovement::factory()->create();

        $route = route('supply-movements.destroy', $supplyMovement);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($supplyMovement);
    }

    public function test_users_cannot_get_a_soft_deleted_supply_movement(): void
    {
        $supplyMovement = SupplyMovement::factory()->create();

        $supplyMovement->delete();

        $route = route('supply-movements.show', $supplyMovement);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
