<?php

namespace Tests\Feature;

use App\Enums\AnimalCategory;
use App\Enums\RevisionResult;
use App\Models\Birth;
use App\Models\Livestock;
use App\Models\Revision;
use App\Models\Service;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    public function test_unauthenticated_users_cannot_access_dashboard_stats(): void
    {
        $route = route('dashboard-stats.index');

        $response = $this->getJson($route);

        $response->assertStatus(401);
    }

    public function test_authenticated_users_can_get_dashboard_stats(): void
    {
        Livestock::factory()->asCow()->count(3)->create();
        Livestock::factory()->asBull()->count(2)->create();

        $route = route('dashboard-stats.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'category_distribution' => [
                    '*' => [
                        'category',
                        'count',
                        'percentage',
                    ],
                ],
            ],
        ]);
    }

    public function test_category_distribution_returns_correct_percentages(): void
    {
        Livestock::factory()->asCow()->count(3)->create();
        Livestock::factory()->asBull()->count(2)->create();

        $route = route('dashboard-stats.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $distribution = $response->json('data.category_distribution');

        $cowEntry = collect($distribution)->firstWhere('category', AnimalCategory::COW->value);
        $bullEntry = collect($distribution)->firstWhere('category', AnimalCategory::BULL->value);

        $this->assertEquals(3, $cowEntry['count']);
        $this->assertEquals(60.0, $cowEntry['percentage']);

        $this->assertEquals(2, $bullEntry['count']);
        $this->assertEquals(40.0, $bullEntry['percentage']);
    }

    public function test_category_distribution_includes_all_categories(): void
    {
        Livestock::factory()->asCow()->create();

        $route = route('dashboard-stats.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $distribution = $response->json('data.category_distribution');
        $categories = collect($distribution)->pluck('category')->toArray();

        foreach (AnimalCategory::cases() as $category) {
            $this->assertContains($category->value, $categories);
        }
    }

    public function test_category_distribution_excludes_dead_animals(): void
    {
        Livestock::factory()->asCow()->count(2)->create();
        Livestock::factory()->asBull()->dead()->create();

        $route = route('dashboard-stats.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $distribution = $response->json('data.category_distribution');
        $cowEntry = collect($distribution)->firstWhere('category', AnimalCategory::COW->value);
        $bullEntry = collect($distribution)->firstWhere('category', AnimalCategory::BULL->value);

        $this->assertEquals(2, $cowEntry['count']);
        $this->assertEquals(100.0, $cowEntry['percentage']);
        $this->assertEquals(0, $bullEntry['count']);
    }

    public function test_category_distribution_returns_empty_when_no_livestock(): void
    {
        $route = route('dashboard-stats.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);
        $response->assertJsonPath('data.category_distribution', []);
    }

    public function test_reproductive_status_distribution_returns_correct_stats(): void
    {
        // 1. Vaca sin eventos (debe ser 'empty')
        $cow1 = Livestock::factory()->asCow()->create();

        // 2. Vaca con revisión 'pregnant' como último evento
        $cow2 = Livestock::factory()->asCow()->create();
        Revision::factory()->create([
            'livestock_id' => $cow2->id,
            'revision_result' => RevisionResult::PREGNANT,
            'made_at' => now()->subDays(5),
        ]);

        // 3. Vaca con revisión 'pregnant' pero con parto posterior (debe ser 'empty')
        $cow3 = Livestock::factory()->asCow()->create();
        Revision::factory()->create([
            'livestock_id' => $cow3->id,
            'revision_result' => RevisionResult::PREGNANT,
            'made_at' => now()->subDays(10),
        ]);
        Birth::factory()->create([
            'mother_id' => $cow3->id,
            'birth_date' => now()->subDays(5),
            'postbirth_revision_date' => now()->subDays(2),
        ]);

        // 4. Vaca con revisión 'pregnant', luego parto, y finalmente servicio posterior (debe ser 'waiting')
        $cow4 = Livestock::factory()->asCow()->create();
        Revision::factory()->create([
            'livestock_id' => $cow4->id,
            'revision_result' => RevisionResult::PREGNANT,
            'made_at' => now()->subDays(15),
        ]);
        Birth::factory()->create([
            'mother_id' => $cow4->id,
            'birth_date' => now()->subDays(10),
            'postbirth_revision_date' => now()->subDays(7),
        ]);
        Service::factory()->create([
            'female_id' => $cow4->id,
            'made_at' => now()->subDays(5),
        ]);

        $route = route('dashboard-stats.index');

        $response = $this->actingAs($this->user)
            ->getJson($route);

        $response->assertStatus(200);

        $distribution = $response->json('data.reproductive_status_distribution');

        $pregnantEntry = collect($distribution)->firstWhere('status', 'pregnant');
        $emptyEntry = collect($distribution)->firstWhere('status', 'empty');
        $waitingEntry = collect($distribution)->firstWhere('status', 'waiting');
        $heatEntry = collect($distribution)->firstWhere('status', 'heat');

        // Total vacas = 4
        // cow1 = empty (1)
        // cow2 = pregnant (1)
        // cow3 = empty (1)
        // cow4 = waiting (1)
        // Resultados: empty = 2 (50.0%), pregnant = 1 (25.0%), waiting = 1 (25.0%), heat = 0 (0.0%)

        $this->assertEquals(1, $pregnantEntry['count']);
        $this->assertEquals(25.0, $pregnantEntry['percentage']);

        $this->assertEquals(2, $emptyEntry['count']);
        $this->assertEquals(50.0, $emptyEntry['percentage']);

        $this->assertEquals(1, $waitingEntry['count']);
        $this->assertEquals(25.0, $waitingEntry['percentage']);

        $this->assertEquals(0, $heatEntry['count']);
        $this->assertEquals(0.0, $heatEntry['percentage']);
    }
}
