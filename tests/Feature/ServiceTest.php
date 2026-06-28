<?php

namespace Tests\Feature;

use App\Models\Livestock;
use App\Models\SemenBatch;
use App\Models\EmbrionBatch;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Technician;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    public function test_users_can_get_a_list_of_services(): void
    {
        Service::factory(3)->create();

        $route = route('services.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_users_can_create_a_new_service_with_natural_mating(): void
    {
        $female = Livestock::factory()->asCow()->create();
        $male = Livestock::factory()->asBull()->create();
        $type = ServiceType::factory()->create();

        $payload = [
            'female_id' => $female->id,
            'parentable_type' => Livestock::class,
            'parentable_id' => $male->id,
            'service_type_id' => $type->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('services.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('services', [
            'female_id' => $female->id,
            'parentable_id' => $male->id,
            'parentable_type' => Livestock::class
        ]);
    }

    public function test_users_can_create_a_new_service_with_artificial_insemination(): void
    {
        $female = Livestock::factory()->asCow()->create();
        $semen = SemenBatch::factory()->create();
        $type = ServiceType::factory()->create();

        $payload = [
            'female_id' => $female->id,
            'parentable_type' => SemenBatch::class,
            'parentable_id' => $semen->id,
            'service_type_id' => $type->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('services.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);
    }

    public function test_it_fails_if_female_is_actually_a_male(): void
    {
        $notAFemale = Livestock::factory()->asBull()->create();
        $male = Livestock::factory()->asBull()->create();
        $type = ServiceType::factory()->create();

        $payload = [
            'female_id' => $notAFemale->id,
            'parentable_type' => Livestock::class,
            'parentable_id' => $male->id,
            'service_type_id' => $type->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('services.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['female_id']);
    }

    public function test_it_fails_if_parentable_livestock_is_actually_a_female(): void
    {
        $female = Livestock::factory()->asCow()->create();
        $notAMale = Livestock::factory()->asCow()->create();
        $type = ServiceType::factory()->create();

        $payload = [
            'female_id' => $female->id,
            'parentable_type' => Livestock::class,
            'parentable_id' => $notAMale->id,
            'service_type_id' => $type->id,
            'made_at' => now()->format('Y-m-d'),
        ];

        $route = route('services.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['parentable_id']);
    }

    public function test_users_can_update_a_service(): void
    {
        $service = Service::factory()->create();
        $newTechnician = Technician::factory()->create();

        $payload = [
            'technician_id' => $newTechnician->id,
        ];

        $route = route('services.update', $service);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);
    }

    public function test_users_can_delete_a_service(): void
    {
        $service = Service::factory()->create();

        $route = route('services.destroy', $service);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);
        $this->assertSoftDeleted($service);
    }

    public function test_users_cannot_get_a_soft_deleted_service(): void
    {
        $service = Service::factory()->create();
        $service->delete();

        $route = route('services.show', $service);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
