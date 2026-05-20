<?php

namespace Tests\Feature;

use App\Models\SemenBatch;
use Tests\TestCase;

class SemenBatchTest extends TestCase
{
    public function test_users_can_get_a_list_of_semen_batches(): void
    {
        SemenBatch::factory(3)->create();

        $route = route('semen-batches.index');

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
                    'livestock_id',
                    'technician_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_semen_batch(): void
    {
        $batch = SemenBatch::factory()->create();

        $route = route('semen-batches.show', $batch);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $batch->code,
            'name' => $batch->name,
            'livestock_id' => $batch->livestock_id,
        ]);
    }

    public function test_users_can_create_a_new_semen_batch(): void
    {
        $payload = SemenBatch::factory()->raw();
        
        $route = route('semen-batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('semen_batches', [
            'code' => $payload['code'],
            'name' => $payload['name'],
            'livestock_id' => $payload['livestock_id'],
        ]);
    }

    public function test_users_can_create_a_new_semen_batch_without_livestock(): void
    {
        $payload = SemenBatch::factory()->withoutLivestock()->raw();

        $route = route('semen-batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('semen_batches', [
            'code' => $payload['code'],
            'livestock_id' => null,
        ]);
    }

    public function test_users_cannot_create_a_new_semen_batch_with_missing_parameters(): void
    {
        $payload = ['name' => 'Test Batch'];

        $route = route('semen-batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_semen_batch(): void
    {
        $batch = SemenBatch::factory()->create();

        $payload = ['name' => 'Updated Batch Name'];

        $route = route('semen-batches.update', $batch);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('semen_batches', [
            'id' => $batch->id,
            'name' => $payload['name']
        ]);
    }

    public function test_users_can_delete_a_semen_batch(): void
    {
        $batch = SemenBatch::factory()->create();

        $route = route('semen-batches.destroy', $batch);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($batch);
    }

    public function test_users_cannot_get_a_soft_deleted_semen_batch(): void
    {
        $batch = SemenBatch::factory()->create();

        $batch->delete();

        $route = route('semen-batches.show', $batch);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
