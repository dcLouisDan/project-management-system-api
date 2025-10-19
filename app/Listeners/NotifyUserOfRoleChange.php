<?php

namespace App\Listeners;

use App\Events\UserRolesAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserOfRoleChange
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
        //
    }
}
