<?php
namespace App\Support;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HcAuth
{
    public $ma_tp;
    public $maquan;
    public $maphuong;

    public $ten_tp;
    public $tenquan;
    public $tenphuong;

    public $user;

    public function __construct(User $user)
    {
        $hcUser = Cache::remember('hc_user.'.$user->id, 30*24*60*60, fn () => $user->hcUser()->with(['tp', 'phuong', 'quan'])->first());

        $this->ma_tp = data_get($hcUser, 'ma_tp');
        $this->maquan = data_get($hcUser, 'maquan');
        $this->maphuong = data_get($hcUser, 'maphuong');

        $this->ten_tp = data_get($hcUser, 'tp.ten_tp');
        $this->tenquan = data_get($hcUser, 'quan.tenquan');
        $this->tenphuong = data_get($hcUser, 'phuong.tenphuong');

        $this->user = $user;

    }

    public function isPhuong(){
        return $this->user->hasRole('phuong-editor');
    }

    public function isQuan(){
        return $this->user->hasRole('quan-editor');
    }

    public function fillModel(&$model){

    }

    public function filterQuery($query, $key = null){
        return $query;
    }

    public function checkHc($model, $key = null){
        return true;
    }
}

