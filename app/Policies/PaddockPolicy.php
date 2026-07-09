<?php

namespace App\Policies;

use App\Models\Paddock;
use App\Models\User;

class PaddockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-batches-paddocks');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paddock $paddock): bool
    {
        return $user->can('manage-batches-paddocks');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-batches-paddocks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paddock $paddock): bool
    {
        return $user->can('manage-batches-paddocks');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paddock $paddock): bool
    {
        return $user->can('manage-batches-paddocks');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Paddock $paddock): bool
    {
        return $user->can('manage-batches-paddocks');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Paddock $paddock): bool
    {
        return $user->can('manage-batches-paddocks');
    }
}
