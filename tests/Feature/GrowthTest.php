<?php

namespace Tests\Feature;

use App\Models\Birth;
use App\Models\Growth;
use App\Models\GrowthType;
use App\Models\Livestock;
use App\Models\Technician;
use Tests\TestCase;

class GrowthTest extends TestCase
{
    public function test_users_can_get_a_list_of_growths(): void
    {
        Growth::factory(3)->create();

        $route = route('growths.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_users_can_get_a_single_growth(): void
    {
        $growth = Growth::factory()->create();

        $route = route('growths.show', $growth);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $growth->id,
            'weight' => (string)$growth->weight
        ]);
    }

    public function test_users_can_create_a_new_growth(): void
    {
        $payload = Growth::factory()->raw();

        $route = route('growths.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('growths', [
            'weight' => $payload['weight'],
            'livestock_id' => $payload['livestock_id']
        ]);
    }

    public function test_users_can_update_a_growth(): void
    {
        $growth = Growth::factory()->create();
        $newWeight = 450.50;

        $payload = [
            'weight' => $newWeight,
        ];

        $route = route('growths.update', $growth);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('growths', [
            'id' => $growth->id,
            'weight' => $newWeight
        ]);
    }

    public function test_users_can_delete_a_growth(): void
    {
        $growth = Growth::factory()->create();

        $route = route('growths.destroy', $growth);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);
        $this->assertSoftDeleted($growth);
    }

    public function test_users_cannot_get_a_soft_deleted_growth(): void
    {
        $growth = Growth::factory()->create();
        $growth->delete();

        $route = route('growths.show', $growth);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }

    public function test_it_validates_polymorphic_growthable_origin(): void
    {
        $birth = Birth::factory()->create();
        
        $payload = Growth::factory()->raw([
            'growthable_type' => Birth::class,
            'growthable_id' => $birth->id
        ]);

        $route = route('growths.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);
    }
}
