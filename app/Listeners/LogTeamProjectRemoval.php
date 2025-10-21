<?php

namespace App\Listeners;

use App\Events\TeamProjectRemoved;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamProjectRemoval
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
    public function handle(TeamProjectRemoved $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->removedBy->id,
            'team_project_removal',
            $event->team,
            $description,
            [],
            ['project' => $event->project],
            null
        );
    }

    protected function buildDescription(TeamProjectRemoved $event): string
    {
        return sprintf(
            "Project '%s' removed from Team '%s' by User '%s'",
            $event->project->name,
            $event->team->name,
            $event->removedBy->name
        );
    }
}
