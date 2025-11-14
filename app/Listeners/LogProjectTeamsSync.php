<?php

namespace App\Listeners;

use App\Events\ProjectTeamsSynced;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogProjectTeamsSync
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
    public function handle(ProjectTeamsSynced $event): void
    {
        $description = $this->buildDescription($event);
        $oldTeams = $event->project->teams()->pluck('teams.id')->toArray();

        ActivityLog::logActivity(
            $event->syncedBy->id,
            'project_teams_sync',
            $event->project,
            $description,
            [],
            $oldTeams,
            $event->currentTeams
        );
    }

    protected function buildDescription(ProjectTeamsSynced $event): string
    {
        $addedCount = count($event->addedTeams);
        $removedCount = count($event->removedTeams);
        $currentCount = count($event->currentTeams);

        return sprintf(
            'Assigned teams to project %s were updated. Added: %d. Removed: %d. Current Total: %d',
            $event->project->name,
            $addedCount,
            $removedCount,
            $currentCount
        );
    }
}
