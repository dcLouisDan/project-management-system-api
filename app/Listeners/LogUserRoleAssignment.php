<?php

namespace App\Listeners;

use App\Events\UserRolesAssigned;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserRoleAssignment
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
    public function handle(UserRolesAssigned $event): void
    {
        $description = $this->buildDescription($event);

        ActivityLog::logActivity(
            $event->assignedBy->id,
            'user_role_assignment',
            $event->user,
            $description,
            [],
            $event->oldRoles,
            $event->newRoles
        );
    }

    /**
     * Build a human-readable description
     */
    protected function buildDescription(UserRolesAssigned $event): string
    {
        $parts = [];

        if ($event->hasAddedRoles()) {
            $parts[] = 'Added: ' . implode(', ', $event->addedRoles());
        }

        if ($event->hasRemovedRoles()) {
            $parts[] = 'Removed: ' . implode(', ', $event->removedRoles());
        }

        return 'User roles updated - ' . implode(' | ', $parts);
    }
}
