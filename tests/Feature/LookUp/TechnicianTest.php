<?php

namespace Tests\Feature\LookUp;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechnicianTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_get_a_list_of_technicians(): void
    {
        Technician::factory(3)->create();

        $route = route('technicians.index');

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

    public function test_unauthenticated_users_cannot_access_technicians_endpoints(): void
    {
        $technician = Technician::factory()->create();

        $routeIndex = route('technicians.index');
        $responseIndex = $this->getJson($routeIndex);
        $responseIndex->assertStatus(401);

        $routeShow = route('technicians.show', $technician);
        $responseShow = $this->getJson($routeShow);
        $responseShow->assertStatus(401);

        $storePayload = [
            'code' => 'sh',
            'name' => 'showTest'
        ];
        $routeStore = route('technicians.store');
        $responseStore = $this->postJson($routeStore, $storePayload);
        $responseStore->assertStatus(401);
        $this->assertDatabaseMissing('technicians', [
            'code' => 'sh'
        ]);

        $updatePayload = [
            'name' => 'test'
        ];
        $routeUpdate = route('technicians.update', $technician);
        $responseUpdate = $this->putJson($routeUpdate, $updatePayload);
        $responseUpdate->assertStatus(401);

        $routeDestroy = route('technicians.destroy', $technician);
        $responseDestroy = $this->deleteJson($routeDestroy);
        $responseDestroy->assertStatus(401);
        $this->assertDatabaseMissing('technicians', [
            'name' => 'test'
        ]);
    }

    public function test_users_can_get_a_single_technician(): void
    {
        $technician = Technician::factory()->create();

        $route = route('technicians.show', $technician);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => $technician->code,
            'name' => $technician->name,
            'telephone' => $technician->telephone,
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

    public function test_users_can_create_a_new_technician(): void
    {
        $payload = Technician::factory()->raw();

        $route = route('technicians.store');

        $response = $this->actingAs($this->user)
             ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('technicians', [
            'code' => $payload['code']
        ]);
    }

    public function test_users_cannot_create_a_new_technician_with_missing_parameters(): void
    {
        $payload = [
            'name' => 'Technician One'
        ];

        $route = route('technicians.store');

        $response = $this->actingAs($this->user)
             ->postJson($route, $payload);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('technicians', [
            'name' => 'Technician One'
        ]);
    }

    public function test_users_cannot_create_a_technician_with_a_duplicated_code(): void
    {
        $technician = Technician::factory()->create();

        $payload = [
            'code' => $technician->code,
            'name' => 'Technician Duplicated'
        ];

        $route = route('technicians.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['code']);
    }

    public function test_users_can_update_a_technician(): void
    {
        $technician = Technician::factory()->create();

        $payload = Technician::factory()->raw();

        $route = route('technicians.update', $technician);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('technicians', [
            'code' => $payload['code']
        ]);
    }

    public function test_users_cannot_update_a_technician_with_missing_parameters(): void
    {
        $technician = Technician::factory()->create();

        $payload = [];

        $route = route('technicians.update', $technician);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_delete_a_technician(): void
    {
        $technician = Technician::factory()->create();

        $route = route('technicians.destroy', $technician);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($technician);
    }

    public function test_users_cannot_get_a_soft_deleted_technician(): void
    {
        $technician = Technician::factory()->create();

        $technician->delete();

        $route = route('technicians.show', $technician);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
