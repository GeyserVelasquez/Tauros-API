<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    /**
     * Display a summary of dashboard statistics.
     */
    public function index(Request $request, DashboardStatsService $service): JsonResponse
    {
        $categoryDistribution = $service->getAnimalCategoryDistribution();
        $reproductiveStatusDistribution = $service->getReproductiveStatusDistribution();

        return response()->json([
            'data' => [
                'category_distribution' => $categoryDistribution,
                'reproductive_status_distribution' => $reproductiveStatusDistribution,
            ],
        ]);
    }
}
