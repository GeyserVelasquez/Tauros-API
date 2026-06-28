<?php

namespace Tests\Feature;

use App\Models\ClinicHistory;
use App\Models\Livestock;
use App\Models\Technician;
use Tests\TestCase;

class ClinicHistoryTest extends TestCase
{
    public function test_users_can_get_a_list_of_clinic_histories(): void
    {
        ClinicHistory::factory(3)->create();

        $route = route('clinic-histories.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_users_can_get_a_single_clinic_history(): void
    {
        $clinicHistory = ClinicHistory::factory()->create();

        $route = route('clinic-histories.show', $clinicHistory);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $clinicHistory->id,
            'code' => $clinicHistory->code
        ]);
    }

    public function test_users_can_create_a_new_clinic_history(): void
    {
        $payload = ClinicHistory::factory()->raw();

        $route = route('clinic-histories.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('clinic_histories', [
            'code' => $payload['code']
        ]);
    }

    public function test_it_fails_if_code_already_exists(): void
    {
        $existing = ClinicHistory::factory()->create();
        
        $payload = ClinicHistory::factory()->raw([
            'code' => $existing->code
        ]);

        $route = route('clinic-histories.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code']);
    }

    public function test_users_can_update_a_clinic_history(): void
    {
        $clinicHistory = ClinicHistory::factory()->create();
        $newName = 'Updated Name';

        $payload = [
            'name' => $newName,
        ];

        $route = route('clinic-histories.update', $clinicHistory);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clinic_histories', [
            'id' => $clinicHistory->id,
            'name' => $newName
        ]);
    }

    public function test_users_can_delete_a_clinic_history(): void
    {
        $clinicHistory = ClinicHistory::factory()->create();

        $route = route('clinic-histories.destroy', $clinicHistory);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);
        $this->assertSoftDeleted($clinicHistory);
    }

    public function test_users_cannot_get_a_soft_deleted_clinic_history(): void
    {
        $clinicHistory = ClinicHistory::factory()->create();
        $clinicHistory->delete();

        $route = route('clinic-histories.show', $clinicHistory);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
