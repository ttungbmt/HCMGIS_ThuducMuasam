<?php
namespace App\Policies;

use App\Models\User;
use Larabase\Policies\Policy;

class CabenhCnPolicy extends Policy
{

    public static $key = 'cabenh_cn';

    public function update(User $user, $model)
    {
        if(hc_auth()->isPhuong()) return hc_auth()->checkHc($model, 'cabenh.dc_maphuong');

        return parent::update($user, $model);
    }

    public function delete(User $user, $model)
    {
        if(hc_auth()->isPhuong()) return false;

        return parent::delete($user, $model);
    }
}
