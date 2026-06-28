<?php

namespace Tests\Feature;

use App\Models\Birth;
use App\Models\Livestock;
use App\Models\Newborn;
use App\Models\NewbornType;
use Tests\TestCase;

class NewbornTest extends TestCase
{
    public function test_users_can_get_a_list_of_newborns(): void
    {
        Newborn::factory(3)->create();

        $route = route('newborns.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_users_can_get_a_single_newborn(): void
    {
        $newborn = Newborn::factory()->create();

        $route = route('newborns.show', $newborn);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $newborn->id,
            'birth_id' => $newborn->birth_id
        ]);
    }

    public function test_users_can_create_a_new_newborn(): void
    {
        $birth = Birth::factory()->create();
        $type = NewbornType::factory()->create();
        $livestock = Livestock::factory()->create();

        $payload = [
            'birth_id' => $birth->id,
            'newborn_type_id' => $type->id,
            'livestock_id' => $livestock->id,
        ];

        $route = route('newborns.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('newborns', [
            'birth_id' => $birth->id,
            'livestock_id' => $livestock->id
        ]);
    }

    public function test_users_can_update_a_newborn(): void
    {
        $newborn = Newborn::factory()->create();
        $newType = NewbornType::factory()->create();

        $payload = [
            'newborn_type_id' => $newType->id,
        ];

        $route = route('newborns.update', $newborn);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);
    }

    public function test_users_can_delete_a_newborn(): void
    {
        $newborn = Newborn::factory()->create();

        $route = route('newborns.destroy', $newborn);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);
        $this->assertSoftDeleted($newborn);
    }

    public function test_users_cannot_get_a_soft_deleted_newborn(): void
    {
        $newborn = Newborn::factory()->create();
        $newborn->delete();

        $route = route('newborns.show', $newborn);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
