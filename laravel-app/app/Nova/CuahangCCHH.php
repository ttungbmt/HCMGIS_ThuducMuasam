<?php

namespace App\Nova;

use App\Nova\Filters\LoaihinhCHFilter;
use App\Support\Directory;
use App\Support\Helper;
use Illuminate\Http\Request;
use Larabase\Nova\Actions\ExportExcel;
use Larabase\Nova\Cards\FiltersSummary;
use Larabase\NovaFields\Text;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;

class CuahangCCHH extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CuahangCCHH::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten_ch';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'ten_ch', 'diachi'
    ];

    public static function label()
    {
        return __('app.cuahang_cchh');
    }

    public function subtitle()
    {
        return "#{$this->id}, {$this->diachi}";
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
            Select::make(__('app.loaihinh'), 'loaihinh')->options(Directory::loaihinh_ch())->displayUsingLabels()->rules('required'),
            Text::make(__('app.ten_ch'), 'ten_ch')->sortable(),
            Text::make(__('app.diachi'), 'diachi')->limit($request->isResourceIndexRequest() ? 15 : 0)->tooltip(['content' => $this->ten_dd]),
            ...Helper::dynamicHcFields(),
            Textarea::make(__('app.hanghoa'), 'hanghoa'),
            Textarea::make(__('app.ghichu'), 'ghichu'),
            new Panel('Mua sắm trực tuyến', [
                Text::make(__('app.web_shopping'), 'web_shopping')->hideFromIndex(),
                Text::make(__('app.hotline_shopping'), 'hotline_shopping')->hideFromIndex(),
                Text::make(__('app.app_shopping'), 'app_shopping')->hideFromIndex(),
            ]),
            HasMany::make(__('app.phantuyen_hh'), 'phantuyens', \App\Nova\PhantuyenHH::class),
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
            (new NovaDetachedFilters([
                (new LoaihinhCHFilter)->withMeta(['width' => 'w-1/3']),
            ]))->width('full'),
            new FiltersSummary,
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
        return [
            (new ExportExcel([
                'serial',
                'loaihinh',
                'ten_ch',
                'diachi',
            ]))
        ];
    }
}
