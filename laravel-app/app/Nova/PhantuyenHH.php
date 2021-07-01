<?php

namespace App\Nova;

use App\Support\Directory;
use App\Support\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Larabase\NovaFields\Text;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;

class PhantuyenHH extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PhantuyenHH::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ten', 'diachi', 'to_dp', 'khupho'
    ];

    public static function label()
    {
        return __('app.phantuyen_hh');
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
            BelongsToMany::make(__('app.cuahang_cchh'), 'cuahang_cchhs', 'App\Nova\CuahangCCHH')->searchable()->withSubtitles(),
            Map::make(__('Map'), 'geom'),
            Select::make(__('app.loaihinh'), 'loaihinh')->options(Directory::phantuyen_type())->displayUsingLabels(),
            Text::make(__('app.ten'), 'ten'),
            Text::make(__('app.diachi'), 'diachi'),
            ...Helper::dynamicHcFields(['ma_tp' => 'nullable', 'maquan' => 'nullable', 'maphuong' => 'nullable']),
            Text::make(__('app.khupho'), 'khupho'),
            Text::make(__('app.to_dp'), 'to_dp')->rules([Rule::unique('phantuyen_hh')->where(function ($query) use ($request) {
                if ($request->input('to_dp')) {
                    $query->where('to_dp', $request->input('to_dp'));
                    $query->where('khupho', $request->input('khupho'));
                    $query->where('maphuong', $request->input('maphuong'));
                } else $query->whereRaw('1=0');
            })
            ]),

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
