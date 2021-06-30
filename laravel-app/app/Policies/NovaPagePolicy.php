<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NovaPagePolicy
{
    public function view(User $user, $name = null){
        if(!$name) return true;

        return $user->hasRole('admin');
    }
}
