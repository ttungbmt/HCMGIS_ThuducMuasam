<?php

namespace App\Nova;

use App\Support\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Larabase\NovaFields\Text;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use MStaack\LaravelPostgis\Geometries\MultiPolygon;
use MStaack\LaravelPostgis\Geometries\Point;
use NovaAjaxSelect\AjaxSelect;

class CachlyPg extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CachlyPg::class;

    public static $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ten', 'diachi',
    ];

    public static function label()
    {
        return __('app.cachly_pg');
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
            Map::make(__('Map'), 'geom'),
            Text::make(__('app.ten'), 'ten'),
            Text::make(__('app.diachi'), 'diachi'),
            BelongsTo::make(__('app.tp'), 'tp', 'App\Nova\HcTp'),

            BelongsTo::make(__('app.qh'), 'quan', 'App\Nova\HcQuan')->exceptOnForms(),
            AjaxSelect::make(__('app.qh'), 'maquan')->get('/api/dir/hc-tp/{tp}/quans')->parent('tp')->nullable(),

            BelongsTo::make(__('app.px'), 'phuong', 'App\Nova\HcPhuong')->exceptOnForms(),
            AjaxSelect::make(__('app.px'), 'maphuong')->get('/api/dir/hc-quan-by-maquan/{maquan}/phuongs')->nullable()->parent('maquan'),

            Trix::make(__('app.ghichu'), 'ghichu')->withFiles('uploads')
        ];
    }

    public static function afterSave(Request $request, $model)
    {
        if($model->maquan && !$model->maphuong){
            $affected = DB::update('UPDATE cachly_pg cl SET geom = pg.geom FROM ranhquan_vn_pg pg WHERE cl.maquan = pg.ma AND cl.id = '.$model->id);
        } elseif ($model->maquan && $model->maphuong){
            $affected = DB::update('UPDATE cachly_pg cl SET geom = pg.geom FROM ranhphuong_vn_pg pg WHERE cl.maphuong = pg.ma AND cl.id = '.$model->id);
        }

        Helper::updateCountLayer(static::$model, 19);
    }

    public static function afterCreate(Request $request, $model)
    {

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
