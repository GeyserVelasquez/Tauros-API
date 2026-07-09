<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that Technician cannot view livestock index (403 Forbidden).
     */
    public function test_technician_cannot_view_livestock_index(): void
    {
        $this->user->syncRoles('Technician');

        $response = $this->actingAs($this->user)
            ->getJson('/livestock');

        $response->assertStatus(403);
    }

    /**
     * Test that Manager can view livestock index (200 OK).
     */
    public function test_manager_can_view_livestock_index(): void
    {
        $this->user->syncRoles('Manager');

        $response = $this->actingAs($this->user)
            ->getJson('/livestock');

        $response->assertStatus(200);
    }

    /**
     * Test that Admin (Super-Admin bypass) can view livestock index (200 OK).
     */
    public function test_admin_can_view_livestock_index_via_bypass(): void
    {
        $this->user->syncRoles('Admin');

        $response = $this->actingAs($this->user)
            ->getJson('/livestock');

        $response->assertStatus(200);
    }
}
