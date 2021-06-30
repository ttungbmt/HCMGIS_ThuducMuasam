<?php

namespace App\Policies;

use App\Models\User;
use Larabase\Policies\Policy;

class CabenhPolicy extends Policy
{
    public static $key = 'cabenh';

    public function update(User $user, $model)
    {
        if(hc_auth()->isPhuong()) return hc_auth()->checkHc($model, 'dc_maphuong');

        return parent::update($user, $model);
    }

    public function delete(User $user, $model)
    {
        if(hc_auth()->isPhuong()) return false;

        return parent::delete($user, $model);
    }

    public function addLayNhiem(User $user){
        return $user->hasRole('editor');
    }

    public function attachAnyCabenh(User $user){
        return $user->hasRole('editor');
    }

    public function attachCabenh(User $user){
        return $user->hasRole('editor');
    }

    public function detachCabenh(User $user){
        return $user->hasRole('editor');
    }
}
