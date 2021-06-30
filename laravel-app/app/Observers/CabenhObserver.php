<?php

namespace App\Observers;

use App\Models\Cabenh;
use App\Models\CabenhCn;
use App\Models\Quanhe;
use App\Support\Directory;
use App\Support\Helper;
use Illuminate\Support\Facades\Cache;
use MStaack\LaravelPostgis\Geometries\Point;


class CabenhObserver
{
    /**
     * Handle the Cabenh "created" event.
     *
     * @param  \App\Models\Cabenh  $cabenh
     * @return void
     */
    public function created(Cabenh $cabenh)
    {
        $nguon_lns = Directory::nguon_ln();
        if($cabenh->nguon_ln && !in_array($cabenh->nguon_ln, $nguon_lns)) Cache::forget('nguon_ln.index');
    }

    /**
     * Handle the Cabenh "updated" event.
     *
     * @param  \App\Models\Cabenh  $cabenh
     * @return void
     */
    public function updated(Cabenh $cabenh)
    {

    }

    /**
     * Handle the Cabenh "deleted" event.
     *
     * @param  \App\Models\Cabenh  $cabenh
     * @return void
     */
    public function deleted(Cabenh $cabenh)
    {
        Helper::updateCountF0();
        Quanhe::where('id', $cabenh->id)->delete();
    }

    /**
     * Handle the Cabenh "restored" event.
     *
     * @param  \App\Models\Cabenh  $cabenh
     * @return void
     */
    public function restored(Cabenh $cabenh)
    {
        //
    }

    /**
     * Handle the Cabenh "force deleted" event.
     *
     * @param  \App\Models\Cabenh  $cabenh
     * @return void
     */
    public function forceDeleted(Cabenh $cabenh)
    {
        //
    }
}
