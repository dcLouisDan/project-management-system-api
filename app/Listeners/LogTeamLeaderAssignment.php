<?php

namespace App\Listeners;

use App\Events\TeamLeaderAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ActivityLog;

class LogTeamLeaderAssignment
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
    public function handle(TeamLeaderAssigned $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->assignedBy->id,
            'team_leader_assignment',
            $event->team,
            $description,
            [],
            $event->previousLeader ? ['team_lead' => $event->previousLeader] : null,
            ['team_lead' => $event->newLeader]
        );
    }

    protected function buildDescription(TeamLeaderAssigned $event): string
    {
        return sprintf(
            'User %s assigned %s as the new team lead of team %s',
            $event->assignedBy->name,
            $event->newLeader->name,
            $event->team->name
        );
    }
}
