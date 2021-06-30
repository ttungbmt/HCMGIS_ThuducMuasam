<?php

namespace App\Nova;

use Larabase\Nova\Filters\MultiSelectFilter;
use Larabase\Nova\Filters\PhuongFilter;
use Larabase\Nova\Filters\QuanFilter;
use Larabase\Nova\Filters\TpFilter;
use App\Support\Directory;
use App\Support\Helper;
use DigitalCreative\ConditionalContainer\ConditionalContainer;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Froala\NovaFroalaField\Froala;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Larabase\Nova\Cards\FiltersSummary;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use OptimistDigital\MultiselectField\Multiselect;

class Diadiem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Diadiem::class;

    public static $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten';

    public static $with = ['phuong'];

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
        return __('app.diadiem');
    }

    public function __construct($resource)
    {
        parent::__construct($resource);
        static::$dirs['tags'] = \App\Models\Tag::all()->pluck('name', 'id');;
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $excepts = ['donvi_ql', 'songuoi_cl', 'congsuat'];

        return [
            ID::make(__('ID'), 'id')->sortable(),
            Map::make(__('Map'), 'geom'),

            Multiselect::make(__('app.loai_dd'), 'tags')
                ->options(static::$dirs['tags'])
                ->displayUsing(fn($value) => $value->map(fn($v) => $v->id))
                ->resolveUsing(fn($value) => $value->map(fn($v) => $v->id))
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->setTagsAttribute(collect($request->input($attribute))->map(fn($id) => data_get(static::$dirs['tags'], $id))->all());
                }),

            Text::make(__('app.ten'), 'ten'),
            Froala::make(__('app.mota'), 'mota')->withFiles('uploads')->alwaysShow()->prunable(),

            new Panel(__('app.diachi'), [
                Text::make(__('app.diachi'), 'diachi'),
                ...Helper::hcFields(),
                Text::make(__('app.khupho'), 'khupho')->hideFromIndex(),
                Text::make(__('app.to_dp'), 'to_dp')->hideFromIndex(),
            ]),

            new Panel(__('app.khac'), [
                NovaDependencyContainer::make([
                    Select::make(__('app.donvi_ql'), 'meta->donvi_ql')->options([1 => 'UBND TP.Thủ Đức', 2 => 'UBND TP.Hồ Chí Minh'])->displayUsingLabels(),
                    Text::make(__('app.songuoi_cl'), 'meta->songuoi_cl'),
                    Text::make(__('app.congsuat'), 'meta->congsuat')
                ])->dependsOn('tags', [1]),

                KeyValue::make(__('Meta'), 'meta')
                    ->displayUsing(fn($value) => $value ? Arr::except($value, $excepts) : null)
                    ->resolveUsing(fn($value) => $value ? Arr::except($value, $excepts) : null)
                    ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                        if ($request->exists($requestAttribute)) {
                            $model->{$attribute} = array_merge($model->{$attribute}->toArray(), json_decode($request->input($attribute), true));
                        }
                    })
            ]),

            new Panel(__('app.hinhanh'), [
                Images::make(__('app.hinhanh'), 'images')->hideFromIndex(),
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
            (new MultiSelectFilter(__('app.loai_dd'), 'loai_dd'))->withOptions(Directory::loai_dd()),
            new TpFilter(),
            new QuanFilter(),
            new PhuongFilter(),
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
