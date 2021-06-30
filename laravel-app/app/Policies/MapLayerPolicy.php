<?php

namespace App\Policies;

use App\Models\User;
use Larabase\Nova\Models\MapLayer;
use Larabase\Policies\Policy;

class MapLayerPolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  MapLayer  $model
     * @return mixed
     */
    public function view(User $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  MapLayer  $model
     * @return mixed
     */
    public function update(User $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  MapLayer  $model
     * @return mixed
     */
    public function delete(User $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  MapLayer  $model
     * @return mixed
     */
    public function restore(User $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  MapLayer  $model
     * @return mixed
     */
    public function forceDelete(User $user, $model)
    {
        return $user->hasRole('admin');
    }
}
