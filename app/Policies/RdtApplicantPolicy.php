<?php

namespace App\Policies;

use App\Entities\RdtApplicant;
use App\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RdtApplicantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Entities\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('manage-applicants');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\RdtApplicant  $rdtApplicant
     * @return mixed
     */
    public function view(User $user, RdtApplicant $rdtApplicant)
    {
        return $user->hasPermission('manage-applicants');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Entities\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('manage-applicants');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\RdtApplicant  $rdtApplicant
     * @return mixed
     */
    public function update(User $user, RdtApplicant $rdtApplicant)
    {
        return $user->hasPermission('manage-applicants');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\RdtApplicant  $rdtApplicant
     * @return mixed
     */
    public function delete(User $user, RdtApplicant $rdtApplicant)
    {
        return $user->hasPermission('manage-applicants');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\RdtApplicant  $rdtApplicant
     * @return mixed
     */
    public function restore(User $user, RdtApplicant $rdtApplicant)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\RdtApplicant  $rdtApplicant
     * @return mixed
     */
    public function forceDelete(User $user, RdtApplicant $rdtApplicant)
    {
        //
    }
}
