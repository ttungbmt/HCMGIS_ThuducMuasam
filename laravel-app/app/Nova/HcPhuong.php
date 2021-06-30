<?php

namespace App\Nova;

use Larabase\Nova\Filters\QuanFilter;
use Larabase\Nova\Filters\TpFilter;
use AwesomeNova\Filters\DependentFilter;
use Illuminate\Http\Request;
use Larabase\Nova\Cards\FiltersSummary;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HcPhuong extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HcPhuong::class;

    public static $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tenphuong';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'maquan', 'tenquan', 'maphuong', 'tenphuong',
    ];

    public static $globallySearchable = false;

    public static function label()
    {
        return __('app.px');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('app.ma_tp'), 'ma_tp'),
            Text::make(__('app.ten_tp'), 'ten_tp'),
            Text::make(__('app.maquan'), 'maquan'),
            Text::make(__('app.tenquan'), 'tenquan'),
            Text::make(__('app.maphuong'), 'maphuong'),
            Text::make(__('app.tenphuong'), 'tenphuong')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            FiltersSummary::make(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new TpFilter(),
            new QuanFilter()
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
