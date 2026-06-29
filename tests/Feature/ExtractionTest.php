<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\EmbrionBatch;
use App\Models\Extraction;
use App\Models\ExtractionType;
use App\Models\Technician;
use Tests\TestCase;

class ExtractionTest extends TestCase
{
    public function test_users_can_get_a_list_of_extractions(): void
    {
        Extraction::factory(3)->create();

        $route = route('extractions.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'batch_type',
                    'batch_id',
                    'technician_id',
                    'extraction_type_id',
                    'made_at'
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_extraction(): void
    {
        $extraction = Extraction::factory()->create();

        $route = route('extractions.show', $extraction);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $extraction->id,
            'batch_id' => $extraction->batch_id
        ]);
    }

    public function test_users_can_create_a_new_extraction_with_batch(): void
    {
        $batch = Batch::factory()->create();
        $type = ExtractionType::factory()->create();
        $technician = Technician::factory()->create();

        $payload = [
            'batch_type' => Batch::class,
            'batch_id' => $batch->id,
            'extraction_type_id' => $type->id,
            'technician_id' => $technician->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('extractions.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('extractions', [
            'batch_id' => $batch->id,
            'batch_type' => Batch::class
        ]);
    }

    public function test_users_can_create_a_new_extraction_with_embrion_batch(): void
    {
        $batch = EmbrionBatch::factory()->create();
        $type = ExtractionType::factory()->create();

        $payload = [
            'batch_type' => EmbrionBatch::class,
            'batch_id' => $batch->id,
            'extraction_type_id' => $type->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('extractions.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('extractions', [
            'batch_id' => $batch->id,
            'batch_type' => EmbrionBatch::class
        ]);
    }

    public function test_it_validates_that_batch_exists(): void
    {
        $type = ExtractionType::factory()->create();

        $payload = [
            'batch_type' => Batch::class,
            'batch_id' => 9999, // Inexistent
            'extraction_type_id' => $type->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('extractions.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['batch_id']);
    }

    public function test_users_can_update_an_extraction(): void
    {
        $extraction = Extraction::factory()->create();
        $newTechnician = Technician::factory()->create();

        $payload = [
            'technician_id' => $newTechnician->id,
        ];

        $route = route('extractions.update', $extraction);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('extractions', [
            'id' => $extraction->id,
            'technician_id' => $newTechnician->id
        ]);
    }

    public function test_users_can_delete_an_extraction(): void
    {
        $extraction = Extraction::factory()->create();

        $route = route('extractions.destroy', $extraction);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($extraction);
    }

    public function test_users_cannot_get_a_soft_deleted_extraction(): void
    {
        $extraction = Extraction::factory()->create();

        $extraction->delete();

        $route = route('extractions.show', $extraction);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
