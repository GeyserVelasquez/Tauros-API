<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Paddock;
use App\Models\Livestock;
use App\Models\PaddockMovement;
use App\Models\BatchMovement;
use App\Models\BatchPaddockMovement;
use App\Services\LivestockMovementService;
use App\Services\BatchMovementService;
use Tests\TestCase;
use Carbon\Carbon;

class MovementServiceTest extends TestCase
{
    private LivestockMovementService $livestockService;
    private BatchMovementService $batchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livestockService = app(LivestockMovementService::class);
        $this->batchService = app(BatchMovementService::class);
    }

    public function test_individual_livestock_can_be_moved_to_paddock(): void
    {
        $livestock = Livestock::factory()->create();
        $paddock = Paddock::factory()->create();
        $madeAt = Carbon::parse('2026-07-05 12:00:00');

        $this->livestockService->moveToPaddock($livestock, $paddock, $madeAt);

        // Check current state
        $this->assertEquals($paddock->id, $livestock->fresh()->paddock_id);

        // Check history
        $this->assertDatabaseHas('paddock_movements', [
            'livestock_id' => $livestock->id,
            'paddock_id' => $paddock->id,
            'made_at' => $madeAt->toDateTimeString(),
        ]);
    }

    public function test_individual_livestock_can_be_moved_to_batch(): void
    {
        $livestock = Livestock::factory()->create();
        $batch = Batch::factory()->create();
        $madeAt = Carbon::parse('2026-07-05 12:00:00');

        $this->livestockService->moveToBatch($livestock, $batch, $madeAt);

        // Check current state
        $this->assertEquals($batch->id, $livestock->fresh()->batch_id);

        // Check history
        $this->assertDatabaseHas('batch_movements', [
            'livestock_id' => $livestock->id,
            'batch_id' => $batch->id,
            'made_at' => $madeAt->toDateString(),
        ]);
    }

    public function test_entire_batch_can_be_moved_to_paddock(): void
    {
        $paddock = Paddock::factory()->create();
        $batch = Batch::factory()->create();
        $madeAt = Carbon::parse('2026-07-05 12:00:00');

        // Create 3 livestock in this batch
        $livestock1 = Livestock::factory()->create(['batch_id' => $batch->id]);
        $livestock2 = Livestock::factory()->create(['batch_id' => $batch->id]);
        $livestock3 = Livestock::factory()->create(['batch_id' => $batch->id]);

        $this->batchService->moveToPaddock($batch, $paddock, $madeAt);

        // Check batch current paddock
        $this->assertEquals($paddock->id, $batch->fresh()->paddock_id);

        // Check batch paddock history
        $this->assertDatabaseHas('batch_paddock_movements', [
            'batch_id' => $batch->id,
            'paddock_id' => $paddock->id,
            'made_at' => $madeAt->toDateTimeString(),
        ]);

        // Check all livestock current paddock updated
        $this->assertEquals($paddock->id, $livestock1->fresh()->paddock_id);
        $this->assertEquals($paddock->id, $livestock2->fresh()->paddock_id);
        $this->assertEquals($paddock->id, $livestock3->fresh()->paddock_id);

        // Check paddock movements history created for each livestock
        $this->assertDatabaseHas('paddock_movements', [
            'livestock_id' => $livestock1->id,
            'paddock_id' => $paddock->id,
            'made_at' => $madeAt->toDateTimeString(),
        ]);
        $this->assertDatabaseHas('paddock_movements', [
            'livestock_id' => $livestock2->id,
            'paddock_id' => $paddock->id,
            'made_at' => $madeAt->toDateTimeString(),
        ]);
        $this->assertDatabaseHas('paddock_movements', [
            'livestock_id' => $livestock3->id,
            'paddock_id' => $paddock->id,
            'made_at' => $madeAt->toDateTimeString(),
        ]);
    }
}
