<?php

namespace App\Observers;

use App\Models\PhongtoaPt;
use App\Support\Helper;

class PhongtoaPtObserver
{
    /**
     * Handle the PhongtoaPt "created" event.
     *
     * @param  \App\Models\PhongtoaPt  $phongtoaPt
     * @return void
     */
    public function created(PhongtoaPt $phongtoaPt)
    {
        Helper::updateCountPhongtoaPt();
    }

    public function saving(PhongtoaPt $phongtoaPt){

    }

    public function creating(PhongtoaPt $phongtoaPt){
        $this->saving($phongtoaPt);
    }

    public function updating(PhongtoaPt $phongtoaPt){
        $this->saving($phongtoaPt);
    }

    /**
     * Handle the PhongtoaPt "updated" event.
     *
     * @param  \App\Models\PhongtoaPt  $phongtoaPt
     * @return void
     */
    public function updated(PhongtoaPt $phongtoaPt)
    {
        if(!$phongtoaPt->originalIsEquivalent('ngaygo_pt')) {
            Helper::updateCountPhongtoaPt();
        }
    }

    /**
     * Handle the PhongtoaPt "deleted" event.
     *
     * @param  \App\Models\PhongtoaPt  $phongtoaPt
     * @return void
     */
    public function deleted(PhongtoaPt $phongtoaPt)
    {
        Helper::updateCountPhongtoaPt();
    }
}
