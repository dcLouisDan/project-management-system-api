<?php

namespace App\Listeners;

use App\Events\TeamMemberAdded;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTeamMemberAddition
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
    public function handle(TeamMemberAdded $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->addedBy->id,
            'team_member_addition',
            $event->team,
            $description,
            [],
            null,
            ['member_id' => $event->member->id, 'role' => $event->role]
        );
    }

    protected function buildDescription(TeamMemberAdded $event): string
    {
        return sprintf(
            'Added member %s to team %s with role %s',
            $event->member->name,
            $event->team->name,
            $event->role
        );
    }
}
