<?php

namespace App\Listeners;

use App\Events\TeamMembersBulkAdded;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamMembersBulkAddition
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
    public function handle(TeamMembersBulkAdded $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->addedBy->id,
            'team_members_bulk_addition',
            $event->team,
            $description,
            [],
            null,
            ['members' => $event->memberIdsWithRoles]
        );
    }

    protected function buildDescription(TeamMembersBulkAdded $event): string
    {
        $memberCount = count($event->memberIdsWithRoles);
        return sprintf(
            'Added %d members to team %s',
            $memberCount,
            $event->team->name
        );
    }
}
