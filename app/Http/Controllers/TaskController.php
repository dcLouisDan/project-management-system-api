<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Tasks Management
 */
class TaskController extends Controller
{
    protected function buildQuery(Request $request, User $user)
    {
        $query = Task::query();

        if (!$user->isAdmin()) {
            $query->where('assigned_to_id', $user->id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to_id', $request->input('assigned_to'));
        }

        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->input('due_date'));
        }

        if ($request->has('due_date_before')) {
            $query->whereDate('due_date', '<', $request->input('due_date_before'));
        }

        if ($request->has('due_date_after')) {
            $query->whereDate('due_date', '>', $request->input('due_date_after'));
        }

        if ($request->has('status_not')) {
            $query->where('status', '!=', $request->input('status_not'));
        }

        if ($request->has('priority_not')) {
            $query->where('priority', '!=', $request->input('priority_not'));
        }

        if ($request->has('assigned_by')) {
            $query->where('assigned_by_id', $request->input('assigned_by'));
        }

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        return $query;
    }


    public function index(Request $request)
    {
        // Code to list tasks
    }
}
