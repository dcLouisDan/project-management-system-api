<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRolesAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user,
        public array $oldRoles,
        public array $newRoles,
        public User $assignedBy
    ) {}

    /**
     * Get the roles that were added
     */
    public function addedRoles(): array
    {
        return array_diff($this->newRoles, $this->oldRoles);
    }

    /**
     * Get the roles that were removed
     */
    public function removedRoles(): array
    {
        return array_diff($this->oldRoles, $this->newRoles);
    }

    /**
     * Check if any roles were added
     */
    public function hasAddedRoles(): bool
    {
        return count($this->addedRoles()) > 0;
    }

    /**
     * Check if any roles were removed
     */
    public function hasRemovedRoles(): bool
    {
        return count($this->removedRoles()) > 0;
    }

    /**
     * Check if specific role was added
     */
    public function roleWasAdded(string $role): bool
    {
        return in_array($role, $this->addedRoles());
    }

    /**
     * Check if specific role was removed
     */
    public function roleWasRemoved(string $role): bool
    {
        return in_array($role, $this->removedRoles());
    }
}
