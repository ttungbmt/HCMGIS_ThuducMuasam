<?php

namespace App\Nova\Filters;

use App\Support\Directory;
use AwesomeNova\Filters\DependentFilter;
use Illuminate\Http\Request;

class TinhtrangPt extends DependentFilter
{
    public function name()
    {
        return __('app.tinhtrang_pt');
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        switch ($value){
            case 1:
                $query->whereNull('ngaygo_pt');
                break;
            case 2:
                $query->whereNotNull('ngaygo_pt');
                break;
            case 3:
                $query->where('lns_count', '>', 1);
                break;
            default:
                break;
        }

        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $filters
     * @return array|\Illuminate\Support\Collection
     */
    public function options(Request $request, array $filters = [])
    {
        return Directory::tinhtrang_pt();
    }
}
