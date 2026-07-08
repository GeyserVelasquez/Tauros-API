<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Paddock;
use App\Models\Livestock;
use Tests\TestCase;

class MovementApiTest extends TestCase
{
    public function test_users_can_move_batch_to_paddock(): void
    {
        $batch = Batch::factory()->create();
        $paddock = Paddock::factory()->create();
        $livestock = Livestock::factory()->create(['batch_id' => $batch->id]);

        $payload = [
            'paddock_id' => $paddock->id,
            'made_at' => now()->format('Y-m-d H:i:s'),
        ];

        $route = route('batches.move', $batch);

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(200);

        $this->assertEquals($paddock->id, $batch->fresh()->paddock_id);
        $this->assertEquals($paddock->id, $livestock->fresh()->paddock_id);

        $this->assertDatabaseHas('batch_paddock_movements', [
            'batch_id' => $batch->id,
            'paddock_id' => $paddock->id,
        ]);

        $this->assertDatabaseHas('paddock_movements', [
            'livestock_id' => $livestock->id,
            'paddock_id' => $paddock->id,
        ]);
    }

    public function test_users_can_move_individual_livestock_to_paddock(): void
    {
        $livestock = Livestock::factory()->create();
        $paddock = Paddock::factory()->create();

        $payload = [
            'paddock_id' => $paddock->id,
            'made_at' => now()->format('Y-m-d H:i:s'),
        ];

        $route = route('livestock.move-paddock', $livestock);

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(200);

        $this->assertEquals($paddock->id, $livestock->fresh()->paddock_id);

        $this->assertDatabaseHas('paddock_movements', [
            'livestock_id' => $livestock->id,
            'paddock_id' => $paddock->id,
        ]);
    }

    public function test_users_can_move_individual_livestock_to_batch(): void
    {
        $livestock = Livestock::factory()->create();
        $batch = Batch::factory()->create();

        $payload = [
            'batch_id' => $batch->id,
            'made_at' => now()->format('Y-m-d H:i:s'),
        ];

        $route = route('livestock.move-batch', $livestock);

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(200);

        $this->assertEquals($batch->id, $livestock->fresh()->batch_id);

        $this->assertDatabaseHas('batch_movements', [
            'livestock_id' => $livestock->id,
            'batch_id' => $batch->id,
        ]);
    }
}
