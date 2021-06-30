<?php

namespace App\Nova;

use App\Notifications\PxStatus;
use App\Support\Directory;
use App\Support\Helper;
use Froala\NovaFroalaField\Froala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Larabase\NovaFields\Radio;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Laynhiem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Laynhiem::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten';

    public static $globallySearchable = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ten',
    ];

    public static function label()
    {
        return __('app.laynhiems');
    }

    public static $displayInNavigation = false;

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
            Map::make(__('Map'), 'geom'),
            Radio::make(__('app.loai_dd'), 'loai_dd')->options(Directory::loai_dd_ln())->rules('required'),
            Text::make(__('app.loaihinh'), 'loaihinh')->suggestions(Directory::loaihinh_dd()),
            Text::make(__('app.ten'), 'ten'),
            Text::make(__('app.diachi'), 'diachi'),
            ...Helper::ajaxHcFields(),
            Text::make(__('app.to_dp'), 'to_dp')->hideFromIndex(),
            Text::make(__('app.khupho'), 'khupho')->hideFromIndex(),
            Froala::make(__('app.ghichu'), 'ghichu')->withFiles('uploads'),
        ];
    }

    public static function afterCreate(Request $request, \App\Models\LayNhiem $model){
        if($model->phuong) {
            $count = Helper::getCountF0ByPx($model->phuong->maphuong);
            if($count % 10 == 0) auth()->user()->notify(new \App\Notifications\PxStatus($model->phuong, $count));
        };
    }

//    public static function afterSave(Request $request, \App\Models\LayNhiem $model){
//        $count = Helper::getCountF0ByPx($model->phuong->maphuong);
//        auth()->user()->notify(new \App\Notifications\PxStatus($model->phuong, $count));
//    }

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
