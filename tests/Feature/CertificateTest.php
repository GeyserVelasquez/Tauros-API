<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Certificate;
use App\Models\Livestock;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    public function test_users_can_get_a_list_of_certificates(): void
    {
        Certificate::factory(3)->create();

        $route = route('certificates.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'certificate_number',
                    'issue_date',
                    'expiry_date',
                    'file_path',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_certificate(): void
    {
        $certificate = Certificate::factory()->create();

        $route = route('certificates.show', $certificate);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'certificate_number' => $certificate->certificate_number,
            'issue_date' => $certificate->issue_date->format('Y-m-d'),
            'expiry_date' => $certificate->expiry_date->format('Y-m-d'),
            'file_path' => $certificate->file_path,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'certificate_number',
                'issue_date',
                'expiry_date',
                'file_path',
            ]
        ]);
    }

    public function test_users_can_create_a_new_certificate(): void
    {
        $payload = Certificate::factory()->raw();

        $route = route('certificates.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('certificates', [
            'certificate_number' => $payload['certificate_number'],
        ]);
    }

    public function test_users_cannot_create_a_new_certificate_with_missing_parameters(): void
    {
        $payload = ['issue_date' => now()->format('Y-m-d')];

        $route = route('certificates.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_certificate(): void
    {
        $certificate = Certificate::factory()->create();

        $payload = ['certificate_number' => 'NEW-NUMBER-123'];

        $route = route('certificates.update', $certificate);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'certificate_number' => $payload['certificate_number']
        ]);
    }

    public function test_users_can_delete_a_certificate(): void
    {
        $certificate = Certificate::factory()->create();

        $route = route('certificates.destroy', $certificate);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($certificate);
    }

    public function test_users_cannot_get_a_soft_deleted_certificate(): void
    {
        $certificate = Certificate::factory()->create();

        $certificate->delete();

        $route = route('certificates.show', $certificate);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }

    public function test_users_can_create_a_certificate_assigned_to_a_batch(): void
    {
        $batch = Batch::factory()->create();
        $livestock1 = Livestock::factory()->create(['batch_id' => $batch->id]);
        $livestock2 = Livestock::factory()->create(['batch_id' => $batch->id]);
        // Livestock not in the batch:
        $livestock3 = Livestock::factory()->create();

        $payload = Certificate::factory()->raw([
            'batch_id' => $batch->id,
        ]);

        $route = route('certificates.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('batch_certificates', [
            'batch_id' => $batch->id,
        ]);

        $this->assertDatabaseHas('livestock_certificates', [
            'livestock_id' => $livestock1->id,
            'batch_id' => $batch->id,
        ]);

        $this->assertDatabaseHas('livestock_certificates', [
            'livestock_id' => $livestock2->id,
            'batch_id' => $batch->id,
        ]);

        $this->assertDatabaseMissing('livestock_certificates', [
            'livestock_id' => $livestock3->id,
        ]);
    }

    public function test_users_can_create_a_certificate_assigned_to_specific_livestock(): void
    {
        $batch = Batch::factory()->create();
        $livestock = Livestock::factory()->create(['batch_id' => $batch->id]);

        $payload = Certificate::factory()->raw([
            'livestock_ids' => [$livestock->id],
        ]);

        $route = route('certificates.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('livestock_certificates', [
            'livestock_id' => $livestock->id,
            'batch_id' => $batch->id,
        ]);
    }

    public function test_livestock_movement_does_not_affect_assigned_certificates_historical_batch(): void
    {
        $batchA = Batch::factory()->create();
        $batchB = Batch::factory()->create();
        $livestock = Livestock::factory()->create(['batch_id' => $batchA->id]);

        $payload = Certificate::factory()->raw([
            'batch_id' => $batchA->id,
        ]);

        $route = route('certificates.store');
        $this->actingAs($this->user)->postJson($route, $payload)->assertStatus(201);

        // Move the livestock to batch B
        $livestock->update(['batch_id' => $batchB->id]);

        // Assert the historical batch_id in the pivot table is still batchA
        $this->assertDatabaseHas('livestock_certificates', [
            'livestock_id' => $livestock->id,
            'batch_id' => $batchA->id,
        ]);
    }

    public function test_users_can_create_a_certificate_without_expiry_date(): void
    {
        $payload = Certificate::factory()->raw([
            'expiry_date' => null,
        ]);

        $route = route('certificates.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('certificates', [
            'certificate_number' => $payload['certificate_number'],
            'expiry_date' => null,
        ]);
    }
}
