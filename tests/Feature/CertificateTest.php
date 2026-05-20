<?php

namespace Tests\Feature;

use App\Models\Certificate;
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
}
