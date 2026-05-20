<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_users_can_get_a_list_of_products(): void
    {
        Product::factory(3)->create();

        $route = route('products.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'code',
                    'name',
                    'description',
                    'attributes',
                    'product_type_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_product(): void
    {
        $product = Product::factory()->create();

        $route = route('products.show', $product);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $product->code,
            'name' => $product->name,
            'product_type_id' => $product->product_type_id,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'description',
                'attributes',
                'product_type_id',
            ]
        ]);
    }

    public function test_users_can_create_a_new_product(): void
    {
        $payload = Product::factory()->raw();
        
        $route = route('products.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'code' => $payload['code'],
            'name' => $payload['name'],
            'product_type_id' => $payload['product_type_id'],
        ]);
    }

    public function test_users_cannot_create_a_new_product_with_missing_parameters(): void
    {
        $payload = ['name' => 'Test Product'];

        $route = route('products.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_product(): void
    {
        $product = Product::factory()->create();

        $payload = ['name' => 'Updated Product Name'];

        $route = route('products.update', $product);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name'
        ]);
    }

    public function test_users_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $route = route('products.destroy', $product);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($product);
    }

    public function test_users_cannot_get_a_soft_deleted_product(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $route = route('products.show', $product);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
