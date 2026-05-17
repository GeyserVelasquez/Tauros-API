<?php

namespace Tests\Feature;

use App\Models\ClinicDiagnostic;
use Tests\TestCase;

class ClinicDiagnosticTest extends TestCase
{
    public function test_users_can_get_a_list_of_clinic_diagnostics(): void
    {
        ClinicDiagnostic::factory(3)->create();

        $route = route('clinic-diagnostics.index');

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
                    'attributes',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_clinic_diagnostic(): void
    {
        $clinicDiagnostic = ClinicDiagnostic::factory()->create();

        $route = route('clinic-diagnostics.show', $clinicDiagnostic);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $clinicDiagnostic->code,
            'name' => $clinicDiagnostic->name,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'attributes',
            ]
        ]);
    }

    public function test_users_can_create_a_new_clinic_diagnostic(): void
    {
        $payload = ClinicDiagnostic::factory()->raw();

        $route = route('clinic-diagnostics.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('clinic_diagnostics', [
            'code' => $payload['code'],
            'name' => $payload['name'],
        ]);
    }

    public function test_users_cannot_create_a_new_clinic_diagnostic_with_missing_parameters(): void
    {
        $payload = ['attributes' => ['foo' => 'bar']];

        $route = route('clinic-diagnostics.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_clinic_diagnostic(): void
    {
        $clinicDiagnostic = ClinicDiagnostic::factory()->create();

        $payload = ['name' => 'Updated Diagnostic Name'];

        $route = route('clinic-diagnostics.update', $clinicDiagnostic);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clinic_diagnostics', [
            'id' => $clinicDiagnostic->id,
            'name' => $payload['name']
        ]);
    }

    public function test_users_can_delete_a_clinic_diagnostic(): void
    {
        $clinicDiagnostic = ClinicDiagnostic::factory()->create();

        $route = route('clinic-diagnostics.destroy', $clinicDiagnostic);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($clinicDiagnostic);
    }

    public function test_users_cannot_get_a_soft_deleted_clinic_diagnostic(): void
    {
        $clinicDiagnostic = ClinicDiagnostic::factory()->create();

        $clinicDiagnostic->delete();

        $route = route('clinic-diagnostics.show', $clinicDiagnostic);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
