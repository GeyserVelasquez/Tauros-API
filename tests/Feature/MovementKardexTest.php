<?php

namespace Tests\Feature;

use App\Models\MovementKardex;
use App\Models\Supply;
use App\Models\SupplyMovement;
use App\Models\Product;
use App\Models\Outcome;
use App\Enums\MovementType;
use Tests\TestCase;

class MovementKardexTest extends TestCase
{
    public function test_users_can_get_a_list_of_movements(): void
    {
        MovementKardex::factory(3)->create();

        $route = route('movement-kardex.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_users_can_get_a_single_movement(): void
    {
        $movement = MovementKardex::factory()->create();

        $route = route('movement-kardex.show', $movement);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $movement->id,
            'quantity' => $movement->quantity
        ]);
    }

    public function test_users_can_create_a_new_movement(): void
    {
        $payload = MovementKardex::factory()->raw();

        $route = route('movement-kardex.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('movement_kardex', [
            'quantity' => $payload['quantity'],
            'item_id' => $payload['item_id']
        ]);
    }

    public function test_users_can_update_a_movement(): void
    {
        $movement = MovementKardex::factory()->create();
        $newQuantity = 500;

        $payload = [
            'quantity' => $newQuantity,
        ];

        $route = route('movement-kardex.update', $movement);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('movement_kardex', [
            'id' => $movement->id,
            'quantity' => $newQuantity
        ]);
    }

    public function test_users_can_delete_a_movement(): void
    {
        $movement = MovementKardex::factory()->create();

        $route = route('movement-kardex.destroy', $movement);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);
        $this->assertSoftDeleted($movement);
    }

    public function test_users_cannot_get_a_soft_deleted_movement(): void
    {
        $movement = MovementKardex::factory()->create();
        $movement->delete();

        $route = route('movement-kardex.show', $movement);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }

    public function test_it_validates_polymorphic_item_and_event(): void
    {
        $product = Product::factory()->create();
        $outcome = Outcome::factory()->create();
        
        $payload = MovementKardex::factory()->raw([
            'item_type' => Product::class,
            'item_id' => $product->id,
            'event_type' => Outcome::class,
            'event_id' => $outcome->id,
            'type' => MovementType::OUTCOME->value
        ]);

        $route = route('movement-kardex.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);
    }
}
