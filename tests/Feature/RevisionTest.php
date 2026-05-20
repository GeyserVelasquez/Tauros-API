<?php

namespace Tests\Feature;

use App\Models\Revision;
use Tests\TestCase;

class RevisionTest extends TestCase
{
    public function test_users_can_get_a_list_of_revisions(): void
    {
        Revision::factory(3)->create();

        $route = route('revisions.index');

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
                    'revision_result',
                    'revision_type_id',
                    'technician_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_revision(): void
    {
        $revision = Revision::factory()->create();

        $route = route('revisions.show', $revision);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'livestock_id' => $revision->livestock_id,
            'made_at' => $revision->made_at->format('Y-m-d'),
            'revision_result' => $revision->revision_result->value,
            'revision_type_id' => $revision->revision_type_id,
            'technician_id' => $revision->technician_id,
        ]);
    }

    public function test_users_can_create_a_new_revision(): void
    {
        $payload = Revision::factory()->raw();
        
        // Factory raw might return Enum object for revision_result, 
        // postJson expects primitives/arrays.
        if ($payload['revision_result'] instanceof \UnitEnum) {
            $payload['revision_result'] = $payload['revision_result']->value;
        }

        $route = route('revisions.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('revisions', [
            'livestock_id' => $payload['livestock_id'],
            'revision_result' => $payload['revision_result'],
        ]);
    }

    public function test_users_cannot_create_a_new_revision_with_missing_parameters(): void
    {
        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('revisions.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_revision(): void
    {
        $revision = Revision::factory()->create();

        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('revisions.update', $revision);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('revisions', [
            'id' => $revision->id,
            'made_at' => $payload['made_at']
        ]);
    }

    public function test_users_can_delete_a_revision(): void
    {
        $revision = Revision::factory()->create();

        $route = route('revisions.destroy', $revision);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($revision);
    }
}
