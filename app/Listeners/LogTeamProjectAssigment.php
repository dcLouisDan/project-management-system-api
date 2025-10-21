<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use App\Events\TeamProjectAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamProjectAssigment
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TeamProjectAssigned $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->assignedBy->id,
            'team_project_assignment',
            $event->team,
            $description,
            [],
            null,
            ['project' => $event->project]
        );
    }

    protected function buildDescription(TeamProjectAssigned $event): string
    {
        return "Project '{$event->project->name}' assigned to Team '{$event->team->name}' by User '{$event->assignedBy->name}'";
    }
}
