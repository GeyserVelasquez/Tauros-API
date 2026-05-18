<?php

use App\Models\Abort;
use Database\Factories\AbortFactory;
use Tests\TestCase;

class AbortTest extends TestCase
{
    public function test_users_can_get_a_list_of_aborts(): void
    {
        Abort::factory(3)->create();

        $route = route('aborts.index');

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
                    'abort_type_id',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_abort(): void
    {
        $abort = Abort::factory()->create();

        $route = route('aborts.show', $abort);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'livestock_id' => $abort->livestock_id,
            'technician_id' => $abort->technician_id,
            'made_at' => $abort->made_at->format('Y-m-d'),
            'abort_type_id' => $abort->abort_type_id,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'livestock_id',
                'technician_id',
                'made_at',
                'abort_type_id',
            ]
        ]);
    }

    public function test_users_can_create_a_new_abort(): void
    {
        $payload = Abort::factory()->raw();

        $route = route('aborts.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('aborts', [
            'livestock_id' => $payload['livestock_id'],
            'abort_type_id' => $payload['abort_type_id'],
        ]);
    }

    public function test_users_cannot_create_a_new_abort_with_missing_parameters(): void
    {
        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('aborts.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_an_abort(): void
    {
        $abort = Abort::factory()->create();

        $payload = ['made_at' => now()->format('Y-m-d')];

        $route = route('aborts.update', $abort);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('aborts', [
            'id' => $abort->id,
            'made_at' => $payload['made_at']
        ]);
    }

    public function test_users_can_delete_an_abort(): void
    {
        $abort = Abort::factory()->create();

        $route = route('aborts.destroy', $abort);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($abort);
    }

    public function test_users_cannot_get_a_soft_deleted_abort(): void
    {
        $abort = Abort::factory()->create();

        $abort->delete();

        $route = route('aborts.show', $abort);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }

    public function test_users_can_create_an_abort_comment(): void
    {
        $payload = Abort::factory()->raw();
        $payload['comment'] = 'Test comment';

        $route = route('aborts.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $abortId = $response->json('data.id');
        $abortMorphClass = (new Abort)->getMorphClass();

        $this->assertDatabaseHas('aborts', [
            'id' => $abortId,
            'livestock_id' => $payload['livestock_id'],
            'abort_type_id' => $payload['abort_type_id'],
        ]);

        $this->assertDatabaseHas('comments', [
            'commentable_id' => $abortId,
            'commentable_type' => $abortMorphClass,
            'text' => $payload['comment'],
            'livestock_id' => $payload['livestock_id'],
        ]);
    }

    public function test_users_can_update_an_abort_comment(): void
    {
        $oldComment = 'Old Comment';
        $abort = Abort::factory()->withComment($oldComment)->create();

        $this->assertDatabaseHas('comments', [
            'commentable_id' => $abort->id,
            'commentable_type' => $abort->getMorphClass(),
            'text' => $oldComment
        ]);

        $payload = [
            'comment' => 'New Comment'
        ];

        $route = route('aborts.update', $abort);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'commentable_id' => $abort->id,
            'commentable_type' => $abort->getMorphClass(),
            'text' => $payload['comment'],
            'livestock_id' => $abort->livestock_id
        ]);

        $this->assertDatabaseMissing('comments', [
            'text' => $oldComment
        ]);
    }

    public function test_users_can_remove_an_abort_comment(): void
    {
        $abort = Abort::factory()->withComment()->create();

        $response = $this->actingAs($this->user)
            ->putJson(route('aborts.update', $abort), ['comment' => null]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', [
            'commentable_id' => $abort->id,
            'commentable_type' => Abort::class
        ]);
    }

}
