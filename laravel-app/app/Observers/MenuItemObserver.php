<?php

namespace App\Observers;


use Illuminate\Support\Facades\Cache;
use OptimistDigital\MenuBuilder\Models\MenuItem;

class MenuItemObserver
{
    /**
     * Handle the MenuItem "created" event.
     *
     * @param MenuItem $menuItem
     * @return void
     */
    public function created(MenuItem $menuItem)
    {
        $this->forgetCache($menuItem);
    }

    /**
     * Handle the MenuItem "updated" event.
     *
     * @param MenuItem $menuItem
     * @return void
     */
    public function updated(MenuItem $menuItem)
    {
        $this->forgetCache($menuItem);
    }

    /**
     * Handle the MenuItem "deleted" event.
     *
     * @param MenuItem $menuItem
     * @return void
     */
    public function deleted(MenuItem $menuItem)
    {
        $this->forgetCache($menuItem);
    }

    protected function forgetCache($menuItem){
        Cache::forget('menus.'.$menuItem->menu->slug);
    }
}
