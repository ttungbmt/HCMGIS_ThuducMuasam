<?php
namespace App\Support;

class HcAuthQuan extends HcAuth {
    public function fillModel(&$model){
        $model->ma_tp = $this->ma_tp;
        $model->maquan = $this->maquan;
    }

    public function filterQuery($query, $key = 'maquan'){
        return $query->where($key, $this->maquan);
    }

    public function checkHc($model, $key = 'maquan'){
        if($maquan = data_get($model, $key)) return $maquan === $this->maquan;
        return true;
    }
}
