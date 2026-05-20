<?php

namespace Tests\Feature;

use App\Models\Outcome;
use Tests\TestCase;

class OutcomeTest extends TestCase
{
    public function test_users_can_get_a_list_of_outcomes(): void
    {
        Outcome::factory(3)->create();

        $route = route('outcomes.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'livestock_id',
                    'made_at',
                    'outcome_type_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_outcome(): void
    {
        $outcome = Outcome::factory()->create();

        $route = route('outcomes.show', $outcome);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'livestock_id' => $outcome->livestock_id,
            'made_at' => $outcome->made_at->format('Y-m-d'),
            'outcome_type_id' => $outcome->outcome_type_id,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'livestock_id',
                'made_at',
                'outcome_type_id',
            ]
        ]);
    }

    public function test_users_can_create_a_new_outcome(): void
    {
        $payload = Outcome::factory()->raw();

        $route = route('outcomes.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('outcomes', [
            'livestock_id' => $payload['livestock_id'],
            'outcome_type_id' => $payload['outcome_type_id'],
        ]);
    }

    public function test_users_cannot_create_a_new_outcome_with_missing_parameters(): void
    {
        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('outcomes.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_an_outcome(): void
    {
        $outcome = Outcome::factory()->create();

        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('outcomes.update', $outcome);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('outcomes', [
            'id' => $outcome->id,
            'made_at' => $payload['made_at']
        ]);
    }

    public function test_users_can_delete_an_outcome(): void
    {
        $outcome = Outcome::factory()->create();

        $route = route('outcomes.destroy', $outcome);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($outcome);
    }

    public function test_users_cannot_get_a_soft_deleted_outcome(): void
    {
        $outcome = Outcome::factory()->create();

        $outcome->delete();

        $route = route('outcomes.show', $outcome);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
