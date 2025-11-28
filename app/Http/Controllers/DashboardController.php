<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function stats(Request $request)
    {
        $user = $request->user();
        $stats = $this->dashboardService->getDashboardStats($user);
        return ApiResponse::success($stats, 'Dashboard stats fetched successfully.');
    }

    public function recentProjects(Request $request)
    {
        $user = $request->user();
        $projects = $this->dashboardService->getRecentProjects($user);
        return ApiResponse::success($projects, 'Recent projects fetched successfully.');
    }

    public function recentTasks(Request $request)
    {
        $user = $request->user();
        $tasks = $this->dashboardService->getRecentTasks($user);
        return ApiResponse::success($tasks, 'Recent tasks fetched successfully.');
    }
}
