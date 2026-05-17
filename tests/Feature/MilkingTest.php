<?php

namespace Tests\Feature;

use App\Models\Livestock;
use App\Models\Milking;
use App\Models\MilkingType;
use App\Models\User;
use Tests\TestCase;

class MilkingTest extends TestCase
{
    public function test_users_can_get_a_list_of_milkings(): void
    {
        Milking::factory(3)->create();

        $route = route('milkings.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'livestock_id',
                    'technician_id',
                    'made_at',
                    'milking_type_id',
                    'first_weight',
                    'second_weight',
                    'third_weight',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_milking(): void
    {
        $milking = Milking::factory()->create();

        $route = route('milkings.show', $milking);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'livestock_id' => $milking->livestock_id,
            'made_at' => $milking->made_at->format('Y-m-d'),
            'milking_type_id' => $milking->milking_type_id,
            'technician_id' => $milking->technician_id,
            'first_weight' => number_format($milking->first_weight, 2, '.', ''),
            'second_weight' => number_format($milking->second_weight, 2, '.', ''),
            'third_weight' => number_format($milking->third_weight, 2, '.', ''),
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'livestock_id',
                'technician_id',
                'made_at',
                'milking_type_id',
                'first_weight',
                'second_weight',
                'third_weight',
            ]
        ]);
    }

    public function test_users_can_create_a_new_milking(): void
    {
        $payload = Milking::factory()->raw();

        $route = route('milkings.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('milkings', [
            'livestock_id' => $payload['livestock_id'],
            'milking_type_id' => $payload['milking_type_id'],
        ]);
    }

    public function test_users_cannot_create_a_new_milking_with_missing_parameters(): void
    {
        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('milkings.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_milking(): void
    {
        $milking = Milking::factory()->create();

        $payload = [
            'first_weight' => 12.0,
        ];

        $route = route('milkings.update', $milking);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('milkings', [
            'id' => $milking->id,
            'first_weight' => 12.0
        ]);
    }

    public function test_users_can_delete_a_milking(): void
    {
        $milking = Milking::factory()->create();

        $route = route('milkings.destroy', $milking);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($milking);
    }

    public function test_users_cannot_get_a_soft_deleted_milking(): void
    {
        $milking = Milking::factory()->create();

        $milking->delete();

        $route = route('milkings.show', $milking);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
