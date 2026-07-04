<?php

namespace App\Services;

use App\Enums\AnimalCategory;
use App\Models\Livestock;
use Illuminate\Support\Facades\DB;

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
     * Get the percentage distribution of each animal category among alive livestock.
     *
     * @return array<int, array{category: string, count: int, percentage: float}>
     */
    public function getAnimalCategoryDistribution(): array
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
     * Get the reproductive status distribution of alive cows.
     *
     * @return array<int, array{status: string, count: int, percentage: float}>
     */
    public function getReproductiveStatusDistribution(): array
    {
        $cows = Livestock::alive()
            ->where('animal_category', AnimalCategory::COW)
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
