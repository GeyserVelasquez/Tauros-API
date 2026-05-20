<?php

use App\Models\Teasing;
use Tests\TestCase;

class TeasingTest extends TestCase
{
    public function test_users_can_get_a_list_of_teasings(): void
    {
        Teasing::factory(3)->create();

        $route = route('teasings.index');

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
                    'detected_at',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_teasing(): void
    {
        $teasing = Teasing::factory()->create();

        $route = route('teasings.show', $teasing);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'livestock_id' => $teasing->livestock_id,
            'technician_id' => $teasing->technician_id,
            'detected_at' => $teasing->detected_at->format('Y-m-d'),
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'livestock_id',
                'technician_id',
                'detected_at',
            ]
        ]);
    }

    public function test_users_can_create_a_new_teasing(): void
    {
        $payload = Teasing::factory()->raw();

        $route = route('teasings.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('teasings', [
            'livestock_id' => $payload['livestock_id'],
            'detected_at' => $payload['detected_at'],
        ]);
    }

    public function test_users_cannot_create_a_new_teasing_with_missing_parameters(): void
    {
        $payload = ['detected_at' => now()->format('Y-m-d')];

        $route = route('teasings.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_an_teasing(): void
    {
        $teasing = Teasing::factory()->create();

        $payload = ['detected_at' => now()->format('Y-m-d')];

        $route = route('teasings.update', $teasing);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('teasings', [
            'id' => $teasing->id,
            'detected_at' => $payload['detected_at']
        ]);
    }

    public function test_users_can_delete_an_teasing(): void
    {
        $teasing = Teasing::factory()->create();

        $route = route('teasings.destroy', $teasing);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($teasing);
    }

    public function test_users_cannot_get_a_soft_deleted_teasing(): void
    {
        $teasing = Teasing::factory()->create();

        $teasing->delete();

        $route = route('teasings.show', $teasing);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
