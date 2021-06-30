<?php

namespace App\Support;


class HcAuthPhuong extends HcAuth {
    public function fillModel(&$model){
        $model->ma_tp = $this->ma_tp;
        $model->maquan = $this->maquan;
        $model->maphuong = $this->maphuong;
    }

    public function filterQuery($query, $key = 'maphuong'){
        return $query->where($key, $this->maphuong);
    }

    public function checkHc($model, $key = 'maphuong'){
        if($maphuong = data_get($model, $key)) return $maphuong === $this->maphuong;
        return true;
    }
}
