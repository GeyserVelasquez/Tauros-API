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
        return response()->json($service->getData());
    }
}
