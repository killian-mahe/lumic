<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can add team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function addTeamMember(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can update team member permissions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function updateTeamMember(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can remove team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function removeTeamMember(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can create links.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return mixed
     */
    public function createLink(User $user, Team $team)
    {
        return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'links:create');
    }

    /**
     * Determine whether the user can edit links.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return mixed
     */
    public function updateLink(User $user, Team $team)
    {
        return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'links:update');
    }

    /**
     * Determine whether the user can delete links.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return mixed
     */
    public function deleteLink(User $user, Team $team)
    {
        return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'links:delete');
    }

    /**
     * Determine whether the user can use links.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return mixed
     */
    public function useLink(User $user, Team $team)
    {
        return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'links:use');
    }
}
