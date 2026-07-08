<?php

namespace App\Services;

use App\Enums\AnimalCategory;
use App\Models\Livestock;
use Illuminate\Support\Facades\DB;
use App\Enums\State;

class DashboardStatsService extends Service
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the distribution statistics of categories, health states, and reproductive status.
     *
     * @return array{data: array{category_distribution: array<int, array{category: string, count: int, percentage: float}>, health_status: array<int, array{category: string, count: int, percentage: float}>, reproductive_status_distribution: array<int, array{status: string, count: int, percentage: float}>}}
     */
    public function getData(): array
    {
        $categoryDistribution = $this->getAnimalCategoryDistribution();
        $healthStatusDistribution = $this->getStatusDistribution();
        $reproductiveStatusDistribution = $this->getReproductiveStatusDistribution();

        return [
            'data' => [
                'category_distribution' => $categoryDistribution,
                'health_status' => $healthStatusDistribution,
                'reproductive_status_distribution' => $reproductiveStatusDistribution,
            ],
        ];
    }

    /**
     * Get the percentage distribution of each animal category among alive livestock.
     *
     * @return array<int, array{category: string, count: int, percentage: float}>
     */
    protected function getAnimalCategoryDistribution(): array
    {
        $totalAlive = Livestock::where('is_alive', true)->count();

        if ($totalAlive === 0) {
            return [];
        }

        $counts = Livestock::where('is_alive', true)
            ->select('animal_category', DB::raw('count(*) as count'))
            ->groupBy('animal_category')
            ->pluck('count', 'animal_category');

        $total = $counts->sum();

        $distribution = [];
        foreach (AnimalCategory::cases() as $category) {
            $count = $counts->get($category->value, 0);
            $distribution[] = [
                'category' => $category->value,
                'count' => (int) $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 2) : 0.0,
            ];
        }

        return $distribution;
    }

    /**
     * Get the percentage distribution of each health state among alive livestock.
     *
     * @return array<int, array{category: string, count: int, percentage: float}>
     */
    protected function getStatusDistribution(): array
    {
        $totalAlive = Livestock::alive()->count();

        if ($totalAlive === 0) {
            return [];
        }

        $counts = Livestock::alive()
            ->select('state', DB::raw('count(*) as count'))
            ->groupBy('state')
            ->pluck('count', 'state');

        $total = $counts->sum();

        $distribution = [];
        foreach (State::cases() as $state) {
            $count = $counts->get($state->value, 0);
            $distribution[] = [
                'category' => $state->value,
                'count' => (int) $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 2) : 0.0,
            ];
        }

        return $distribution;
    }

    /**
     * Get the reproductive status distribution of alive cows.
     *
     * @return array<int, array{status: string, count: int, percentage: float}>
     */
    protected function getReproductiveStatusDistribution(): array
    {
        $cows = Livestock::alive()
            ->where('animal_category', AnimalCategory::COW)
            ->orWhere('animal_category', AnimalCategory::HEIFER)
            ->with(['latestEvent.eventable'])
            ->get();

        $totalCows = $cows->count();

        if ($totalCows === 0) {
            return [];
        }

        $counts = [
            'pregnant' => 0,
            'heat' => 0,
            'empty' => 0,
            'waiting' => 0,
        ];

        foreach ($cows as $cow) {
            $status = $cow->reproductive_status;
            if (array_key_exists($status, $counts)) {
                $counts[$status]++;
            }
        }

        $distribution = [];
        foreach ($counts as $status => $count) {
            $distribution[] = [
                'status' => $status,
                'count' => $count,
                'percentage' => $totalCows > 0 ? round(($count / $totalCows) * 100, 2) : 0.0,
            ];
        }

        return $distribution;
    }
}
