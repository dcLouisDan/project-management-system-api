<?php

namespace App\Listeners;

use App\Events\TeamLeaderDemoted;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamLeaderDemotion
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
    public function handle(TeamLeaderDemoted $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->demotedBy->id,
            'team_leader_demotion',
            $event->team,
            $description,
            [],
            ['team_lead' => $event->demotedLeader],
            null
        );
    }

    protected function buildDescription(TeamLeaderDemoted $event): string
    {
        return sprintf(
            'User %s was demoted from team lead of team %s by %s',
            $event->demotedLeader->name,
            $event->team->name,
            $event->demotedBy->name
        );
    }
}
