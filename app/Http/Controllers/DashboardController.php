<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Services\DashboardService;
use Illuminate\Http\Request;

/**
 * @group Dashboard
 *
 * APIs for managing dashboard
 */

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    /**
     * Get Dashboard Stats
     *
     * Get the dashboard stats for the current user.
     *
     * @queryParam force_role string Filter the stats by role. Example: admin
     * 
     * @apiResourceCollection App\Http\Resources\DashboardResource
     *
     * @apiResourceModel App\Models\Dashboard 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function stats(Request $request)
    {
        $user = $request->user();
        $stats = $this->dashboardService->getDashboardStats($user, $request->input('force_role'));
        return ApiResponse::success($stats, 'Dashboard stats fetched successfully.');
    }

    /**
     * Get Recent Projects
     *
     * Get the recent projects for the current user.
     *
     * @queryParam force_role string Filter the projects by role. Example: admin
     * @queryParam limit integer Limit the number of projects to return. Example: 5
     *
     * 
     * @apiResourceCollection App\Http\Resources\ProjectResource
     *
     * @apiResourceModel App\Models\Project
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentProjects(Request $request)
    {
        $forceRole = $request->input('force_role');
        $limit = $request->input('limit', 5);

        $user = $request->user();
        $projects = $this->dashboardService->getRecentProjects($user, $limit, $forceRole);
        return ApiResponse::success($projects, 'Recent projects fetched successfully.');
    }

    /**
     * Get Recent Tasks
     *
     * Get the recent tasks for the current user.
     *
     * @queryParam force_role string Filter the tasks by role. Example: admin
     * @queryParam limit integer Limit the number of tasks to return. Example: 5
     *
     * @apiResourceCollection App\Http\Resources\TaskResource
     * 
     * @apiResourceModel App\Models\Task 
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentTasks(Request $request)
    {
        $forceRole = $request->input('force_role');
        $limit = $request->input('limit', 5);
        $user = $request->user();
        $tasks = $this->dashboardService->getRecentTasks($user, $limit, $forceRole);
        return ApiResponse::success($tasks, 'Recent tasks fetched successfully.');
    }
}
