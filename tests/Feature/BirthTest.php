<?php

namespace Tests\Feature;

use App\Models\Birth;
use App\Models\BirthType;
use App\Models\Livestock;
use App\Models\Technician;
use Tests\TestCase;

class BirthTest extends TestCase
{
    public function test_users_can_get_a_list_of_births(): void
    {
        Birth::factory(3)->create();

        $route = route('births.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_users_can_get_a_single_birth(): void
    {
        $birth = Birth::factory()->create();

        $route = route('births.show', $birth);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $birth->id,
            'mother_id' => $birth->mother_id
        ]);
    }

    public function test_users_can_create_a_new_birth(): void
    {
        $payload = Birth::factory()->raw();

        $route = route('births.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('births', [
            'mother_id' => $payload['mother_id'],
            'birth_type_id' => $payload['birth_type_id']
        ]);
    }

    public function test_it_fails_if_mother_is_male(): void
    {
        $notAMother = Livestock::factory()->asBull()->create();
        
        $payload = Birth::factory()->raw([
            'mother_id' => $notAMother->id
        ]);

        $route = route('births.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['mother_id']);
    }

    public function test_users_can_update_a_birth(): void
    {
        $birth = Birth::factory()->create();
        $newTechnician = Technician::factory()->create();

        $payload = [
            'technician_id' => $newTechnician->id,
        ];

        $route = route('births.update', $birth);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('births', [
            'id' => $birth->id,
            'technician_id' => $newTechnician->id
        ]);
    }

    public function test_users_can_delete_a_birth(): void
    {
        $birth = Birth::factory()->create();

        $route = route('births.destroy', $birth);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);
        $this->assertSoftDeleted($birth);
    }

    public function test_users_cannot_get_a_soft_deleted_birth(): void
    {
        $birth = Birth::factory()->create();
        $birth->delete();

        $route = route('births.show', $birth);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
