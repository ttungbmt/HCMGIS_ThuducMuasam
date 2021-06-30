<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Larabase\NovaFields\RelationshipCount;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HcTp extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HcTp::class;

    public static $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten_tp';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ma_tp', 'ten_tp',
    ];

    public static $globallySearchable = false;

    public static function label()
    {
        return __('app.tp');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('quans as quans');
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
            RelationshipCount::make(__('app.quan_count'), 'quans')->sortable(),
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
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
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
