<?php

namespace App\Listeners;

use App\Events\TeamMembersBulkRemoved;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamMembersBulkRemoval
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
    public function handle(TeamMembersBulkRemoved $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->removedBy->id,
            'team_members_bulk_removal',
            $event->team,
            $description,
            [],
            ['removed_members' => $event->removedMembers],
            null
        );
    }

    protected function buildDescription(TeamMembersBulkRemoved $event): string
    {
        $removedCount = count($event->removedMembers);
        return "Removed $removedCount members from team {$event->team->name} by {$event->removedBy->name}.";
    }
}
