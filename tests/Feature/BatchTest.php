<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\User;
use Tests\TestCase;

class BatchTest extends TestCase
{
    public function testBasic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_users_can_get_a_list_of_batches(): void
    {
        Batch::factory(3)->create();

        $route = route('batches.index');

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
                    'paddock_id'
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_batch(): void
    {
        $batch = Batch::factory()->create();

        $route = route('batches.show', $batch);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $batch->code,
            'name' => $batch->name,
            'paddock_id' => $batch->paddock_id
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'paddock_id'
            ]
        ]);
    }

    public function test_users_can_create_a_new_batch(): void
    {
        $payload = Batch::factory()->raw();

        $route = route('batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('batches', [
            'code' => $payload['code']
        ]);
    }

    public function test_users_cannot_create_a_new_batch_with_missing_parameters(): void
    {
        $payload = ['code' => '0295'];

        $route = route('batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_batch(): void
    {
        $batch = Batch::factory()->create();

        $payload = [
            'code' => '0295',
            'name' => 'Macanao'
        ];

        $route = route('batches.update', $batch);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('batches', [
            'code' => '0295'
        ]);
    }

    public function test_users_can_delete_a_batch(): void
    {
        $batch = Batch::factory()->create();

        $route = route('batches.destroy', $batch);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($batch);
    }

    public function test_users_cannot_get_a_soft_deleted_batch(): void
    {
        $batch = Batch::factory()->create();

        $batch->delete();

        $route = route('batches.show', $batch);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
