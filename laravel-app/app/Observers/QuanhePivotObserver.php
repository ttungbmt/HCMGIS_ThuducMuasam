<?php
namespace App\Observers;

use App\Models\QuanhePivot;
use App\Models\Quanhe;

class QuanhePivotObserver
{
    /**
     * Handle the QuanhePivot "created" event.
     *
     * @param  \App\Models\QuanhePivot  $cabenhPivot
     * @return void
     */
    public function created(QuanhePivot $cabenhPivot)
    {
        $parent = Quanhe::firstOrCreate(['id' => $cabenhPivot->parent_id]);
        $parent->fixTree();
    }

    /**
     * Handle the QuanhePivot "updated" event.
     *
     * @param  \App\Models\QuanhePivot  $cabenhPivot
     * @return void
     */
    public function updated(QuanhePivot $cabenhPivot)
    {
        //
    }

    /**
     * Handle the QuanhePivot "deleted" event.
     *
     * @param  \App\Models\QuanhePivot  $cabenhPivot
     * @return void
     */
    public function deleted(QuanhePivot $cabenhPivot)
    {
        $tree = Quanhe::descendantsAndSelf($parent_id = $cabenhPivot->parent_id)->toTree($parent_id);
        if($tree->isEmpty() && $qh = Quanhe::find($parent_id)) $qh->delete();
    }

    /**
     * Handle the QuanhePivot "restored" event.
     *
     * @param  \App\Models\QuanhePivot  $cabenhPivot
     * @return void
     */
    public function restored(QuanhePivot $cabenhPivot)
    {
        //
    }

    /**
     * Handle the QuanhePivot "force deleted" event.
     *
     * @param  \App\Models\QuanhePivot  $cabenhPivot
     * @return void
     */
    public function forceDeleted(QuanhePivot $cabenhPivot)
    {
        //
    }
}
