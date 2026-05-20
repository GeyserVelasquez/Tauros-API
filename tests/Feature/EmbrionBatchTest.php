<?php

namespace Tests\Feature;

use App\Models\EmbrionBatch;
use Tests\TestCase;

class EmbrionBatchTest extends TestCase
{
    public function test_users_can_get_a_list_of_embrion_batches(): void
    {
        EmbrionBatch::factory(3)->create();

        $route = route('embrion-batches.index');

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
                    'mother_id',
                    'father_id',
                    'technician_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_embrion_batch(): void
    {
        $batch = EmbrionBatch::factory()->create();

        $route = route('embrion-batches.show', $batch);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $batch->id,
            'code' => $batch->code,
            'name' => $batch->name,
            'description' => $batch->description,
            'mother_id' => $batch->mother_id,
            'father_id' => $batch->father_id,
            'technician_id' => $batch->technician_id,
        ]);
    }

    public function test_users_can_create_a_new_embrion_batch(): void
    {
        $payload = EmbrionBatch::factory()->raw();

        $route = route('embrion-batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('embrion_batches', [
            'code' => $payload['code'],
            'name' => $payload['name'],
            'description' => $payload['description'],
            'mother_id' => $payload['mother_id'],
            'father_id' => $payload['father_id'],
            'technician_id' => $payload['technician_id'],
        ]);
    }

    public function test_users_can_create_a_new_embrion_batch_without_parents(): void
    {
        $payload = EmbrionBatch::factory()->withoutLivestockParents()->raw();

        $route = route('embrion-batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('embrion_batches', [
            'code' => $payload['code'],
            'name' => $payload['name'],
            'description' => $payload['description'],
            'mother_id' => null,
            'father_id' => null,
            'technician_id' => $payload['technician_id'],
        ]);
    }

    public function test_users_cannot_create_a_new_embrion_batch_with_missing_parameters(): void
    {
        $payload = ['name' => 'Test Embrion Batch'];

        $route = route('embrion-batches.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_embrion_batch(): void
    {
        $batch = EmbrionBatch::factory()->create();

        $payload = [
            'code' => 'EB-UPDATED',
            'name' => 'Updated Embrion Batch Name',
            'description' => 'Updated Description',
        ];

        $route = route('embrion-batches.update', $batch);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('embrion_batches', [
            'id' => $batch->id,
            'code' => $payload['code'],
            'name' => $payload['name'],
            'description' => $payload['description'],
        ]);
    }

    public function test_users_can_delete_a_embrion_batch(): void
    {
        $batch = EmbrionBatch::factory()->create();

        $route = route('embrion-batches.destroy', $batch);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($batch);
    }

    public function test_users_cannot_get_a_soft_deleted_embrion_batch(): void
    {
        $batch = EmbrionBatch::factory()->create();

        $batch->delete();

        $route = route('embrion-batches.show', $batch);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
