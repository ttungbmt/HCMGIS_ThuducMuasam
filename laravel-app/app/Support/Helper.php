<?php

namespace App\Support;

use App\Models\Cabenh;
use AwesomeNova\Filters\DependentFilter;
use Hubertnnn\LaravelNova\Fields\DynamicSelect\DynamicSelect;
use Illuminate\Http\Request;
use Larabase\Nova\Filters\QuanFilter;
use Larabase\Nova\MenuItemTypes\InternalUrl;
use Larabase\Nova\MenuItemTypes\Resource;
use Larabase\Nova\MenuItemTypes\Tool;
use App\Models\HcQuan;
use App\Models\HcTp;
use App\Models\HcPhuong;
use DigitalCreative\CollapsibleResourceManager\Resources\ExternalLink;
use DigitalCreative\CollapsibleResourceManager\Resources\InternalLink;
use DigitalCreative\CollapsibleResourceManager\Resources\RawResource;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Larabase\Nova\Models\MapLayer;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Text;
use NovaAjaxSelect\AjaxSelect;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;

class Helper
{
    public static function hcFields()
    {
        $hcTps = \App\Models\HcTp::all(['id', 'ma_tp', 'ten_tp']);
        return [
            NovaBelongsToDepend::make(__('app.tp'), 'tp', \App\Nova\HcTp::class)
                ->options($hcTps)
                ->placeholder('Chọn ' . Str::lower(__('app.tp')))
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = data_get(HcTp::find($request->input($requestAttribute)), 'ma_tp');
                })->hideFromIndex(),
            NovaBelongsToDepend::make(__('app.qh'), 'quan', \App\Nova\HcQuan::class)
                ->optionsResolve(function (HcTp $tp) {
                    return $tp->quans()->get(['id', 'maquan', 'tenquan']);
                })
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = data_get(HcQuan::find($request->input($requestAttribute)), 'maquan');
                })
                ->placeholder('Chọn ' . Str::lower(__('app.qh')))
                ->dependsOn('tp')
                ->hideFromIndex(),
            NovaBelongsToDepend::make(__('app.px'), 'phuong', \App\Nova\HcPhuong::class)
                ->optionsResolve(function (HcQuan $quan) {
                    return $quan->phuongs()->get(['id', 'maphuong', 'tenphuong']);
                })
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = data_get(HcPhuong::find($request->input($requestAttribute)), 'maphuong');
                })
                ->placeholder('Chọn ' . Str::lower(__('app.px')))
                ->dependsOn('quan'),
        ];
    }

    public static function dynamicHcFields($rules = ['ma_tp' => 'required', 'maquan' => 'required', 'maphuong' => 'required'])
    {
        $nullable = fn($name) => isset($rules[$name]) && Str::contains($rules[$name], 'required') ? $rules[$name] : 'nullable';

        $fields = collect([
            DynamicSelect::make(__('app.tp'), 'ma_tp')
                ->placeholder('Chọn ' . Str::lower(__('app.tp')))
                ->options(fn($values) => \App\Models\HcTp::all($columns = ['ten_tp', 'ma_tp'])->pluck(...$columns))
                ->rules($nullable('ma_tp'))->hideFromIndex(),
            DynamicSelect::make(__('app.qh'), 'maquan')
                ->placeholder('Chọn ' . Str::lower(__('app.qh')))
                ->dependsOn(['ma_tp'])
                ->options(fn($values) => \App\Models\HcQuan::where('ma_tp', $values['ma_tp'])->get($columns = ['tenquan', 'maquan'])->pluck(...$columns))
                ->rules($nullable('maquan'))->hideFromIndex(),
            DynamicSelect::make(__('app.px'), 'maphuong')
                ->placeholder('Chọn ' . Str::lower(__('app.px')))
                ->dependsOn(['maquan'])
                ->options(fn($values) => \App\Models\HcPhuong::where('maquan', $values['maquan'])->get($columns = ['tenphuong', 'maphuong'])->pluck(...$columns))
                ->rules($nullable('maphuong')),
        ]);

        if(hc_auth()->isPhuong()) $fields = collect([
            Hidden::make(__('app.px'), 'maphuong')->fillUsing(fn ($request, $model, $attribute, $requestAttribute)=> hc_auth()->fillModel($model)),
            Text::make(__('app.px'), 'phuong.tenphuong')->default(hc_auth()->tenphuong)->readonly()
        ]);

        return $fields->all();
    }

    public static function ajaxHcFields()
    {
        return [
            BelongsTo::make(__('app.tp'), 'tp', 'App\Nova\HcTp')->hideFromIndex(),

            BelongsTo::make(__('app.qh'), 'quan', 'App\Nova\HcQuan')->exceptOnForms()->hideFromIndex(),
            AjaxSelect::make(__('app.qh'), 'maquan')->get('/api/dir/hc-tp/{tp}/quans')->parent('tp')->nullable()->rules('required'),

            BelongsTo::make(__('app.px'), 'phuong', 'App\Nova\HcPhuong')->exceptOnForms(),
            AjaxSelect::make(__('app.px'), 'maphuong')->get('/api/dir/hc-quan-by-maquan/{maquan}/phuongs')->nullable()->parent('maquan')->rules('required'),

//            BelongsTo::make(__('app.tp'), 'tp', 'App\Nova\HcTp')->hideFromIndex(),
//            AjaxSelect::make(__('app.tp'), 'ma_tp')->get('/api/dir/hc-tp')->rules('required')->hideFromIndex(),

//            BelongsTo::make(__('app.qh'), 'quan', 'App\Nova\HcQuan')->exceptOnForms()->hideFromIndex(),
//            AjaxSelect::make(__('app.qh'), 'maquan')->get('/api/dir/hc-tp/{ma_tp}/quans')->parent('ma_tp')->nullable()->rules('required'),
//            AjaxSelect::make(__('app.qh'), 'maquan')->get('/api/dir/hc-tp-by-maquan/{ma_tp}/quans')->parent('ma_tp')->nullable()->rules('required'),

//            BelongsTo::make(__('app.px'), 'phuong', 'App\Nova\HcPhuong')->exceptOnForms(),
//            AjaxSelect::make(__('app.px'), 'maphuong')->get('/api/dir/hc-quan-by-maquan/{maquan}/phuongs')->nullable()->parent('maquan')->rules('required'),
//            AjaxSelect::make(__('app.px'), 'maphuong')->get('/api/dir/hc-quan-by-maquan/{maquan}/phuongs')->nullable()->parent('maquan')->rules('required'),
        ];
    }


    public static function updateCountF0()
    {
        $count = Cabenh::where('phanloai_cl', 'F0')->count();
        $layer = MapLayer::find(16);
        $layer->data = array_merge($layer->data, ['count' => $count]);
        $layer->save();
    }

    public static function updateCountPhongtoaPt()
    {
        $count = DB::table('v_phongtoa_pt')->where('go_pt', 0)->count();
        $layer = MapLayer::find(15);
        $layer->data = array_merge($layer->data, ['count' => $count]);
        $layer->save();
    }

    public static function updateCountLayer($class, $id)
    {
        $count = $class::count();
        $layer = MapLayer::find($id);
        $layer->data = array_merge($layer->data, ['count' => $count]);
        $layer->save();
    }

    public static function getCountF0ByPx($maphuong)
    {
        $query = DB::table('laynhiem', 'ln')
            ->selectRaw('count(*)')
            ->leftJoin('cabenh AS cb', 'cb.id', '=', 'ln.cabenh_id')
            ->where('maphuong', $maphuong)
            ->where('cb.phanloai_cl', 'F0');

        return data_get($query->first(), 'count');
    }


    public static function quanFilter()
    {
        return (new QuanFilter)->dependentOf([])->withOptions(fn(Request $request) => Directory::quans());
    }

}
