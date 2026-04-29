<?php

namespace Tests\Feature\LookUp;

use App\Models\Technique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechniqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_get_a_list_of_techniques(): void
    {
        Technique::factory(3)->create();

        $route = route('techniques.index');

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
                    'telephone',
                ]
            ]
        ]);
    }

    public function test_unauthenticated_users_cannot_access_techniques_endpoints(): void
    {
        $technique = Technique::factory()->create();

        $routeIndex = route('techniques.index');
        $responseIndex = $this->getJson($routeIndex);
        $responseIndex->assertStatus(401);

        $routeShow = route('techniques.show', $technique);
        $responseShow = $this->getJson($routeShow);
        $responseShow->assertStatus(401);

        $storePayload = [
            'code' => 'sh',
            'name' => 'showTest'
        ];
        $routeStore = route('techniques.store');
        $responseStore = $this->postJson($routeStore, $storePayload);
        $responseStore->assertStatus(401);
        $this->assertDatabaseMissing('techniques', [
            'code' => 'sh'
        ]);

        $updatePayload = [
            'name' => 'test'
        ];
        $routeUpdate = route('techniques.update', $technique);
        $responseUpdate = $this->putJson($routeUpdate, $updatePayload);
        $responseUpdate->assertStatus(401);

        $routeDestroy = route('techniques.destroy', $technique);
        $responseDestroy = $this->deleteJson($routeDestroy);
        $responseDestroy->assertStatus(401);
        $this->assertDatabaseMissing('techniques', [
            'name' => 'test'
        ]);
    }

    public function test_users_can_get_a_single_technique(): void
    {
        $technique = Technique::factory()->create();

        $route = route('techniques.show', $technique);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $technique->code,
            'name' => $technique->name,
            'telephone' => $technique->telephone,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'telephone',
            ]
        ]);
    }

    public function test_users_can_create_a_new_technique(): void
    {
        $payload = Technique::factory()->raw();

        $route = route('techniques.store');

        $response = $this->actingAs($this->user)
             ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('techniques', [
            'code' => $payload['code']
        ]);
    }

    public function test_users_cannot_create_a_new_technique_with_missing_parameters(): void
    {
        $payload = [
            'name' => 'Technique One'
        ];

        $route = route('techniques.store');

        $response = $this->actingAs($this->user)
             ->postJson($route, $payload);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('techniques', [
            'name' => 'Technique One'
        ]);
    }

    public function test_users_cannot_create_a_technique_with_a_duplicated_code(): void
    {
        $technique = Technique::factory()->create();

        $payload = [
            'code' => $technique->code,
            'name' => 'Technique Duplicated'
        ];

        $route = route('techniques.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['code']);
    }

    public function test_users_can_update_a_technique(): void
    {
        $technique = Technique::factory()->create();

        $payload = Technique::factory()->raw();

        $route = route('techniques.update', $technique);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('techniques', [
            'code' => $payload['code']
        ]);
    }

    public function test_users_cannot_update_a_technique_with_missing_parameters(): void
    {
        $technique = Technique::factory()->create();

        $payload = [];

        $route = route('techniques.update', $technique);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_delete_a_technique(): void
    {
        $technique = Technique::factory()->create();

        $route = route('techniques.destroy', $technique);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($technique);
    }

    public function test_users_cannot_get_a_soft_deleted_technique(): void
    {
        $technique = Technique::factory()->create();

        $technique->delete();

        $route = route('techniques.show', $technique);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
