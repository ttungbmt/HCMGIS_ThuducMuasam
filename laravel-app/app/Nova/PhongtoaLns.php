<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Larabase\NovaFields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class PhongtoaLns extends Resource
{
    public static function label()
    {
        return 'Lần phong tỏa';
    }


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PhongtoaLns::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ngay_pt';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ngay_pt', 'ngaygo_pt'
    ];

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
            Date::make(__('app.ngay_pt'), 'ngay_pt')->rules('required'),
            Date::make(__('app.ngaygo_pt'), 'ngaygo_pt'),
            Textarea::make(__('app.ghichu'), 'ghichu')
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
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
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
