<?php

namespace Tests\Feature;

use App\Models\ProductMovement;
use Tests\TestCase;

class ProductMovementTest extends TestCase
{
    public function test_users_can_get_a_list_of_product_movements(): void
    {
        ProductMovement::factory(3)->create();

        $route = route('product-movements.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'product_id',
                    'type',
                    'made_at',
                    'attributes',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_product_movement(): void
    {
        $movement = ProductMovement::factory()->create();

        $route = route('product-movements.show', $movement);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $movement->id,
            'product_id' => $movement->product_id,
        ]);
    }

    public function test_users_can_create_a_new_product_movement(): void
    {
        $payload = ProductMovement::factory()->raw();
        
        $route = route('product-movements.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('product_movements', [
            'product_id' => $payload['product_id'],
            'type' => $payload['type'],
        ]);
    }

    public function test_users_can_update_a_product_movement(): void
    {
        $movement = ProductMovement::factory()->create();

        $payload = ['type' => 'loss'];

        $route = route('product-movements.update', $movement);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('product_movements', [
            'id' => $movement->id,
            'type' => 'loss'
        ]);
    }

    public function test_users_can_delete_a_product_movement(): void
    {
        $movement = ProductMovement::factory()->create();

        $route = route('product-movements.destroy', $movement);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($movement);
    }

    public function test_users_cannot_get_a_soft_deleted_product_movement(): void
    {
        $movement = ProductMovement::factory()->create();

        $movement->delete();

        $route = route('product-movements.show', $movement);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
