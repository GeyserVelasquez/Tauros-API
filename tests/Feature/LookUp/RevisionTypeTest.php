<?php

namespace Tests\Feature\LookUp;

use App\Models\RevisionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevisionTypeTest extends TestCase
{

    public function test_users_can_get_a_list_of_revision_types(): void
    {

        RevisionType::factory(3)->create();

        $route = route('revision-types.index');

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
                ]
            ]
        ]);
    }

    public function test_unauthenticated_users_cannot_access_revision_types_endpoints(): void
    {
        $revisionType = RevisionType::factory()->create();

        $routeIndex = route('revision-types.index');
        $responseIndex = $this->getJson($routeIndex);
        $responseIndex->assertStatus(401);

        $routeShow = route('revision-types.show', $revisionType);
        $responseShow = $this->getJson($routeShow);
        $responseShow->assertStatus(401);

        $storePayload = [
            'code' => 'sh',
            'name' => 'showTest'
        ];
        $routeStore = route('revision-types.store');
        $responseStore = $this->postJson($routeStore, $storePayload);
        $responseStore->assertStatus(401);
        $this->assertDatabaseMissing('revision_types', [
            'code' => 'sh'
        ]);


        $updatePayload = [
            'name' => 'test'
        ];
        $routeUpdate = route('revision-types.update', $revisionType);
        $responseUpdate = $this->putJson($routeUpdate, $updatePayload);
        $responseUpdate->assertStatus(401);

        $routeDestroy = route('revision-types.destroy', $revisionType);
        $responseDestroy = $this->deleteJson($routeDestroy);
        $responseDestroy->assertStatus(401);
        $this->assertDatabaseMissing('revision_types', [
            'name' => 'test'
        ]);
    }

    public function test_users_can_get_a_single_revisionType(): void
    {
        $revisionType = RevisionType::factory()->create();

        $route = route('revision-types.show', $revisionType);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $revisionType->code,
            'name' => $revisionType->name
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
            ]
        ]);
    }

    public function test_users_can_create_a_new_revisionType(): void
    {
        $payload = [
            'code' => 'HO',
            'name' => 'Holstein'
        ];

        $route = route('revision-types.store');

        $response = $this->actingAs($this->user)
             ->postJson($route,$payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('revision_types', [
            'code' => 'HO'
        ]);
    }

    public function test_users_cannot_create_a_new_revisionType_with_missing_parameters(): void
    {
        $payload = [
            'name' => 'Holstein'
        ];

        $route = route('revision-types.store');

        $response = $this->actingAs($this->user)
             ->postJson($route,$payload);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('revision_types', [
            'name' => 'Holstein'
        ]);
    }

    public function test_users_cannot_create_a_revisionType_with_a_duplicated_code(): void
    {
        $revisionType = RevisionType::factory()->create();

        $payload = [
            'code' => $revisionType->code,
            'name' => 'Modificado'
        ];

        $route = route('revision-types.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['code']);
    }

    public function test_users_can_update_a_revisionType(): void
    {
        $revisionType = RevisionType::factory()->create();

        $payload = [
            'code' => 'WG',
            'name' => 'Wagyu'
        ];

        $route = route('revision-types.update', $revisionType);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('revision_types', [
            'code' => 'WG'
        ]);
    }

    public function test_users_cannot_update_a_revisionType_with_missing_parameters(): void
    {
        $revisionType = RevisionType::factory()->create();

        $payload = [];

        $route = route('revision-types.update', $revisionType);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(422);

        $this->assertDatabaseHas($revisionType);
    }

    public function test_users_can_delete_a_revisionType(): void
    {
        $revisionType = RevisionType::factory()->create([
            'code' => 'AN',
            'name' => 'Angus'
        ]);

        $route = route('revision-types.destroy', $revisionType);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($revisionType);
    }

    public function test_users_cannot_get_a_soft_deleted_revisionType(): void
    {
        $revisionType = RevisionType::factory()->create();

        $revisionType->delete();

        $route = route('revision-types.show', $revisionType);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }

}
