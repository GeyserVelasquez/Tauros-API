<?php

namespace App\Policies;

use App\Models\Growth;
use App\Models\User;

class GrowthPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('register-growths');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Growth $growth): bool
    {
        return $user->can('register-growths');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('register-growths');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Growth $growth): bool
    {
        return $user->can('register-growths');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Growth $growth): bool
    {
        return $user->can('register-growths');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Growth $growth): bool
    {
        return $user->can('register-growths');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Growth $growth): bool
    {
        return $user->can('register-growths');
    }
}
