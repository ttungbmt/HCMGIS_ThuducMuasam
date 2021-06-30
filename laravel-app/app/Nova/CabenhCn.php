<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Larabase\NovaFields\Text;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use MStaack\LaravelPostgis\Geometries\Point;
use OptimistDigital\MultiselectField\Multiselect;

class CabenhCn extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CabenhCn::class;

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
        'id',
    ];

    public static $searchRelations = [
        'cabenh' => ['hoten', 'dc_diachi'],
    ];

    public static function label()
    {
        return __('app.cabenh_cn');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('cabenh.phuong')->whereHas('cabenh.phuong', fn($q) => hc_auth()->filterQuery($q));
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
            Map::make(__('Map'), 'cabenh.geom')->exceptOnForms(),
            Multiselect::make(__('app.cabenh_cn'), 'cabenh_ids')->asyncResource(Cabenh::class)->rules('required')->reorderable()->hideFromIndex(),
            Text::make(__('app.cabenh_count'), 'cabenh_ids')->displayUsing(fn ($value) => collect(json_decode($value))->count())->onlyOnIndex(),
            Text::make(__('Ca khởi phát'), 'cabenh.hoten')->linkResource(['/cabenhs', data_get($this, 'cabenh.id')])->exceptOnForms(),
            Text::make(__('app.diachi'), 'cabenh.dc_diachi')->exceptOnForms(),
            Text::make(__('app.px'), 'cabenh.phuong.tenphuong')->exceptOnForms(),
        ];
    }

    public static function beforeSave(Request $request, $model)
    {
        $ids = collect($request->input('cabenh_ids'));
        $diachi = \App\Models\Cabenh::whereIn('id', $ids)->whereNotNull('geom')->first();
        $model->cabenh_id = $diachi->id;
        $model->count = $ids->count();
    }

    public static function afterSave(Request $request, $model)
    {
        $ids = collect(json_decode($model->cabenh_ids));
        \App\Models\Cabenh::whereIn('id', $ids)->update(['cabenh_cn_id' => 1]);
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
