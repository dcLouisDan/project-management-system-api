<?php

namespace App\Listeners;

use App\Events\TeamMemberRemoved;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamMemberRemoval
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
    public function handle(TeamMemberRemoved $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->removedBy->id,
            'team_member_removal',
            $event->team,
            $description,
            [],
            ['removed_member' => $event->removedMember],
            null
        );
    }

    protected function buildDescription(TeamMemberRemoved $event): string
    {
        return sprintf(
            'User %s was removed from team %s by %s',
            $event->removedMember->name,
            $event->team->name,
            $event->removedBy->name
        );
    }
}
