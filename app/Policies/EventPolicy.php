<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Authorization as Organization;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the organization can view any models.
     */
    public function viewAny(Organization $organization): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the organization can view the model.
     */
    public function view(Organization $organization, Event $event): Response
    {
        return $organization->id === $event->organization_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
        //
    }

    /**
     * Determine whether the organization can create models.
     */
    public function create(Organization $organization): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the organization can update the model.
     */
    public function update(Organization $organization, Event $event): Response
    {
        return $organization->id === $event->organization_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Determine whether the organization can delete the model.
     */
    public function delete(Organization $organization, Event $event): Response
    {
        return $organization->id === $event->organization_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Determine whether the organization can restore the model.
     */
    public function restore(Organization $organization, Event $event): Response
    {
        return $organization->id === $event->organization_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Determine whether the organization can permanently delete the model.
     */
    public function forceDelete(Organization $organization, Event $event): Response
    {
        return $organization->id === $event->organization_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }
}
