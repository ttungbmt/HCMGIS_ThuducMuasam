<?php

namespace App\Observers;

use App\Models\CabenhCn;
use App\Support\Helper;

class CabenhCnObserver
{
    protected $modelClass = '\App\Models\CabenhCn';
    /**
     * Handle the CabenhCn "created" event.
     *
     * @param  \App\Models\CabenhCn  $model
     * @return void
     */
    public function created(CabenhCn $model)
    {
        Helper::updateCountLayer($this->modelClass, 25);
    }

    /**
     * Handle the CabenhCn "updated" event.
     *
     * @param  \App\Models\CabenhCn  $model
     * @return void
     */
    public function updated(CabenhCn $model)
    {
        Helper::updateCountLayer($this->modelClass, 25);
    }

    /**
     * Handle the CabenhCn "deleted" event.
     *
     * @param  \App\Models\CabenhCn  $model
     * @return void
     */
    public function deleted(CabenhCn $model)
    {
        Helper::updateCountLayer($this->modelClass, 25);
    }

    /**
     * Handle the CabenhCn "restored" event.
     *
     * @param  \App\Models\CabenhCn  $model
     * @return void
     */
    public function restored(CabenhCn $model)
    {
        //
    }

    /**
     * Handle the CabenhCn "force deleted" event.
     *
     * @param  \App\Models\CabenhCn  $model
     * @return void
     */
    public function forceDeleted(CabenhCn $model)
    {
        //
    }
}
