<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResource;
use App\Http\Responses\ApiResponse;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Activity Log Management
 *
 * APIs for managing activity logs
 */

class ActivityLogController extends Controller
{

    public function __construct(protected ActivityLogService $activityLogService) {}



    /**
     * List Activity Logs
     *
     * Get a paginated list of all activity logs with optional filtering.
     *
     * @queryParam per_page integer Number of activity logs per page. Defaults to 10. Example: 15
     * @queryParam user_id integer Filter activity logs by user ID. Example: 1
     * @queryParam action string Filter activity logs by action. Example: created
     * @queryParam model string Filter activity logs by model. Example: user
     * @queryParam model_id integer Filter activity logs by model ID. Example: 1
     * @queryParam description string Filter activity logs by description. Example: User created
     * @queryParam created_at_from date Filter activity logs by created at (from). Example: 2024-01-01
     * @queryParam created_at_to date Filter activity logs by created at (to). Example: 2024-12-31
     *
     * @apiResourceCollection App\Http\Resources\ActivityLogResource
     *
     * @apiResourceModel App\Models\ActivityLog paginate=10
     *
     * @response status=200 scenario="success" {"data": [{"id": 1, "user_id": 1, "action": "created", "model": "user", "model_id": 1, "description": "User created", "created_at": "2024-01-01 12:00:00", "updated_at": "2024-01-01 12:00:00"}], "links": {}, "meta": {}}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve activity logs", "errors": [], "meta": []}
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', ActivityLog::class)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }
        $perPage = $request->input('per_page', 10);
        $query = $this->activityLogService->buildFilteredQuery($request->all());
        try {
            return ActivityLogResource::collection($query->paginate($perPage));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve activity logs', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);
            return ApiResponse::error('Failed to retrieve activity logs', 500);
        }
    }
}
