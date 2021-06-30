<?php

namespace App\Nova;

use Larabase\Nova\Filters\TpFilter;
use Illuminate\Http\Request;
use Larabase\Nova\Cards\FiltersSummary;
use Larabase\NovaFields\RelationshipCount;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HcQuan extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HcQuan::class;

    public static $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tenquan';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ma_tp', 'ten_tp',  'maquan', 'tenquan'
    ];

    public static $globallySearchable = false;

    public static function label()
    {
        return __('app.qh');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('phuongs as phuongs');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
            RelationshipCount::make(__('app.phuong_count'), 'phuongs')->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new TpFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
