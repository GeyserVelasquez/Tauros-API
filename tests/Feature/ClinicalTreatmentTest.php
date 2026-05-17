<?php

namespace Tests\Feature;

use App\Models\ClinicalTreatment;
use Tests\TestCase;

class ClinicalTreatmentTest extends TestCase
{
    public function test_users_can_get_a_list_of_clinical_treatments(): void
    {
        ClinicalTreatment::factory(3)->create();

        $route = route('clinical-treatments.index');

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

    public function test_users_can_get_a_single_clinical_treatment(): void
    {
        $clinicalTreatment = ClinicalTreatment::factory()->create();

        $route = route('clinical-treatments.show', $clinicalTreatment);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $clinicalTreatment->code,
            'name' => $clinicalTreatment->name,
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

    public function test_users_can_create_a_new_clinical_treatment(): void
    {
        $payload = ClinicalTreatment::factory()->raw();

        $route = route('clinical-treatments.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('clinical_treatments', [
            'code' => $payload['code'],
            'name' => $payload['name'],
        ]);
    }

    public function test_users_cannot_create_a_new_clinical_treatment_with_missing_parameters(): void
    {
        $payload = ['attributes' => ['foo' => 'bar']];

        $route = route('clinical-treatments.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_clinical_treatment(): void
    {
        $clinicalTreatment = ClinicalTreatment::factory()->create();

        $payload = ['name' => 'Updated Treatment Name'];

        $route = route('clinical-treatments.update', $clinicalTreatment);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clinical_treatments', [
            'id' => $clinicalTreatment->id,
            'name' => $payload['name']
        ]);
    }

    public function test_users_can_delete_a_clinical_treatment(): void
    {
        $clinicalTreatment = ClinicalTreatment::factory()->create();

        $route = route('clinical-treatments.destroy', $clinicalTreatment);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($clinicalTreatment);
    }

    public function test_users_cannot_get_a_soft_deleted_clinical_treatment(): void
    {
        $clinicalTreatment = ClinicalTreatment::factory()->create();

        $clinicalTreatment->delete();

        $route = route('clinical-treatments.show', $clinicalTreatment);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
