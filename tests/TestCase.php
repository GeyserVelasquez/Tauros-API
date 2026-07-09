<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected string $apiPrefix = '/api/v1';

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeader('Referer', 'http://localhost');

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->user = User::factory()->create();
        $this->user->assignRole('Admin');
    }

    protected function buildApiUri(string $uri): string
    {
        if (str_starts_with($uri, 'http') || str_starts_with($uri, $this->apiPrefix)) {
            return $uri;
        }

        return $this->apiPrefix.'/'.ltrim($uri, '/');
    }

    /**
     * GET
     */
    public function getJson($uri, array $headers = [], $options = 0): TestResponse
    {
        return parent::getJson($this->buildApiUri($uri), $headers, $options);
    }

    /**
     * POST
     */
    public function postJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::postJson($this->buildApiUri($uri), $data, $headers, $options);
    }

    /**
     * PUT
     */
    public function putJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::putJson($this->buildApiUri($uri), $data, $headers, $options);
    }

    /**
     * PATCH
     */
    public function patchJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::patchJson($this->buildApiUri($uri), $data, $headers, $options);
    }

    /**
     * DELETE
     */
    public function deleteJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::deleteJson($this->buildApiUri($uri), $data, $headers, $options);
    }

    /**
     * OPTIONS
     */
    public function optionsJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::optionsJson($this->buildApiUri($uri), $data, $headers, $options);
    }
}
