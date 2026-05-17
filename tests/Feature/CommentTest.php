<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Livestock;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_users_can_get_a_list_of_comments(): void
    {
        Comment::factory(3)->create();

        $route = route('comments.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'text',
                    'livestock_id',
                    'commentable_id',
                    'commentable_type',
                ]
            ]
        ]);
    }

    public function test_users_can_get_a_single_comment(): void
    {
        $comment = Comment::factory()->create();

        $route = route('comments.show', $comment);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $comment->id,
            'text' => $comment->text,
            'livestock_id' => $comment->livestock_id,
            'commentable_id' => $comment->commentable_id,
            'commentable_type' => $comment->commentable_type,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'text',
                'livestock_id',
                'commentable_id',
                'commentable_type',
            ]
        ]);
    }

    public function test_users_can_create_a_new_comment(): void
    {
        $payload = Comment::factory()->raw();

        $route = route('comments.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'text' => $payload['text'],
            'livestock_id' => $payload['livestock_id'],
            'commentable_id' => $payload['commentable_id'],
            'commentable_type' => $payload['commentable_type'],
        ]);
    }

    public function test_users_cannot_create_a_new_comment_with_missing_parameters(): void
    {
        $payload = ['text' => 'Some comment text'];

        $route = route('comments.store');

        $response = $this->actingAs($this->user)
            ->postJson($route, $payload);

        $response->assertStatus(422);
    }

    public function test_users_can_update_a_comment(): void
    {
        $comment = Comment::factory()->create();

        $payload = [
            'text' => 'Updated comment text'
        ];

        $route = route('comments.update', $comment);

        $response = $this->actingAs($this->user)
            ->putJson($route, $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'text' => $payload['text']
        ]);
    }

    public function test_users_can_delete_a_comment(): void
    {
        $comment = Comment::factory()->create();

        $route = route('comments.destroy', $comment);

        $response = $this->actingAs($this->user)
            ->deleteJson($route);

        $response->assertStatus(204);

        $this->assertSoftDeleted($comment);
    }

    public function test_users_cannot_get_a_soft_deleted_comment(): void
    {
        $comment = Comment::factory()->create();

        $comment->delete();

        $route = route('comments.show', $comment);

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(404);
    }
}
