<?php

namespace App\Nova;

use App\Models\Quanhe;
use App\Nova\Actions\ImportCabenh;
use App\Nova\Filters\NoiPhFilter;
use App\Nova\Filters\PhanloaiClFilter;
use App\Policies\CabenhPolicy;
use Larabase\Nova\Filters\DateRangeFilter;
use Larabase\Nova\Filters\MultiSelectFilter;
use App\Support\Directory;
use App\Support\Helper;
use AwesomeNova\Filters\DependentFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tabs;
use Froala\NovaFroalaField\Froala;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Larabase\Nova\Actions\ExportExcel;
use Larabase\Nova\Cards\FiltersSummary;
use Larabase\NovaAmcharts\Amcharts;
use Larabase\NovaFields\Date;
use Larabase\NovaFields\Email;
use Larabase\NovaFields\Integer;
use Larabase\NovaFields\Radio;
use Larabase\NovaFields\Serial;
use Larabase\NovaFields\Text;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;
use Whitecube\NovaFlexibleContent\Flexible;

class Cabenh extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Cabenh::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'hoten';

    public static $with = ['phuong', 'quan'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'hoten', 'ma_qg', 'ma_kv'
    ];

    public function __construct($resource)
    {
        parent::__construct($resource);

        static::$dirs['phanloai_cl'] = collect(Directory::phanloai_cl())->values();
        static::$dirs['nguon_ln'] = \App\Support\Directory::nguon_ln();
    }

    public function subtitle()
    {
        return collect([$this->dc_diachi, data_get($this->phuong, 'tenphuong'), data_get($this->quan, 'tenquan')])->filter()->implode(', ');
    }


    public function title()
    {
        return $this->hoten . ($this->ma_qg ? " - {$this->ma_qg}" : '');
    }

    public static function label()
    {
        return __('app.cabenh');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if(hc_auth()->isPhuong()) return hc_auth()->filterQuery($query, 'dc_maphuong');
        elseif (hc_auth()->isQuan()) return hc_auth()->filterQuery($query, 'dc_maquan');

        return $query;
    }


    public static function relatableQuery(NovaRequest $request, $query)
    {
        if ($request->is('nova-api/cabenhs/*/attachable/cabenhs')) {
            return $query->whereKeyNot($request->segment(3));
        }

        return $query;
    }

    protected function toSodoLn($items, $level = 5)
    {
        $data = [];
        $fn_name = function ($cb) {
            $ten = Str::of($cb['hoten'])->explode(' ')->last();
            if ($cb['ma_kv']) return $cb['ma_kv'] . ": {$ten}";
            return $cb['hoten'];
        };

        foreach ($items as $k => $i) {
            $cabenh = data_get($i, 'cabenh');
            $cabenhLinks = data_get($i, 'cabenh_links');

            $data[$k]['name'] = $fn_name($cabenh);
            $data[$k]['value'] = $level / 2;
            $data[$k]['meta'] = $cabenh;
            if ($cabenhLinks) {
                $data[$k]['linkWith'] = collect($cabenhLinks)->map(fn($v) => $fn_name($v['cabenh']))->toArray();
            }

            if (isset($i['children']) && !empty($i['children'])) {
                $data[$k]['children'] = $this->toSodoLn($i['children'], $data[$k]['value']);
            }
        }
        return $data;
    }

    protected function getRoot($model)
    {
        $parent = $model->ancestors()->first();
        if ($parent->isRoot()) return $parent;
        return $this->getRoot($parent);
    }

    protected function getSodo($id)
    {
        $network = [];
        $model = Quanhe::find($id);
        if (!$model) return [];
        if (!$model->isRoot()) $model = $this->getRoot($model);

        $query = Quanhe::with(['cabenh', 'cabenhLinks.cabenh'])->descendantsAndSelf($model->id);
        $quanhe = $query->toTree()->toArray();
        $quanheFlat = $query->toFlatTree()->toArray();
        $linkIds = collect($quanheFlat)->pluck('cabenh_links')->filter()->collapse()->pluck('cabenh_link_id')->unique();

        $network = $quanhe;

        $lmodels = collect();
        foreach ($linkIds as $linkId) {
            $lmodel = Quanhe::find($linkId);
            if (!$lmodel) {
                $cabenh = \App\Models\Cabenh::find($linkId);
                $network[] = [
                    'id' => $cabenh->id,
                    'cabenh' => $cabenh
                ];
                continue;
            }
            if (!$lmodel->isRoot()) $lmodel = $this->getRoot($lmodel);
            $lmodels[] = $lmodel;
        }

        // Filter unique root
        $lmodels = $lmodels->filter(fn($m) => $m->id !== $model->id)->unique('id');
        if ($lmodels->isNotEmpty()) {
            foreach ($lmodels as $m) {
                $quanhe = Quanhe::with(['cabenh', 'cabenhLinks.cabenh'])->descendantsAndSelf($m->id)->toTree()->toArray();
                array_push($network, $quanhe);
            }
        }

        return $this->toSodoLn($network);
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
            Serial::make('STT', 'serial')->onlyOnExport(),

            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Map::make(__('Map'), 'geom')->onlyOnIndex(),

            new Panel('Sơ đồ lây nhiễm', [
                Amcharts::make('Sơ đồ', 'sodo_ln')->resolveUsing(fn($value) => $this->getSodo($this->id))
                    ->withTooltip('#{meta.id} {meta.hoten} {meta.ma_qg}')->onlyOnDetail(),
            ]),

            (new Panel('Thông tin ca bệnh', collect([
                Text::make(__('app.ma_qg'), 'ma_qg'),
                Text::make(__('app.ma_kv'), 'ma_kv'),
                Text::make(__('app.hoten'), 'hoten')->rules('required')->showOnIndex()->link($this),
                Select::make(__('app.phanloai_cl'), 'phanloai_cl')->options(Directory::phanloai_cl())->default('F0')->nullable()->showOnIndex(),
                Select::make(__('app.noi_ph'), 'noi_ph')->options(Directory::noi_ph()),
                Text::make(__('app.nguon_ln'), 'nguon_ln')->suggestions(static::$dirs['nguon_ln']),
                Date::make(__('app.ngay_ntt'), 'ngay_ntt')->rules('required'),
                Date::make(__('app.ngay_cb'), 'ngay_cb'),
                Text::make(__('app.tinh_cb'), 'tinh_cb'),
                Textarea::make(__('app.yeuto_dt'), 'yeuto_dt'),
                Froala::make(__('app.ghichu'), 'ghichu')->withFiles('uploads'),
                Images::make(__('app.hinhanh'), 'images')->hideFromIndex(),
            ])
                ->map(fn($f) => in_array($f->attribute, [
                    'hoten', 'ma_kv', 'phanloai_cl', 'ngay_ntt'
                ]) ? $f : $f->hideFromIndex()->showOnExport())
            )),

            (new Panel('Địa chỉ cư trú', collect([
                Map::make(__('Map'), 'geom'),
                Text::make(__('app.ten'), 'dc_ten'),
                Multiselect::make(__('app.loaihinh'), 'dc_loaihinh')->options(Directory::loaihinh_dd())->singleSelect(),
                Text::make(__('app.diachi'), 'dc_diachi')->rules('required'),
                AjaxSelect::make(__('app.tp'), 'dc_ma_tp')->get('/api/dir/hc-tp')->rules('required'),
                BelongsTo::make(__('app.qh'), 'quan', 'App\Nova\HcQuan')->exceptOnForms()->hideFromIndex(),
                AjaxSelect::make(__('app.qh'), 'dc_maquan')->get('/api/dir/hc-tp-by-maquan/{dc_ma_tp}/quans')->parent('dc_ma_tp')->nullable()->rules('required'),
                BelongsTo::make(__('app.px'), 'phuong', 'App\Nova\HcPhuong')->exceptOnForms(),
                AjaxSelect::make(__('app.px'), 'dc_maphuong')->get('/api/dir/hc-quan-by-maquan/{dc_maquan}/phuongs')->nullable()->parent('dc_maquan')->rules('required'),
                Text::make(__('app.to_dp'), 'dc_to_dp'),
                Text::make(__('app.khupho'), 'dc_khupho'),
            ])
                ->map(fn($f) => in_array($f->attribute, [
                    'phuong'
                ]) ? $f : $f->hideFromIndex()->showOnExport())
            )),

            (new Panel('Thông tin cá nhân', collect([
                Date::make(__('app.ngaysinh'), 'ngaysinh'),
                Integer::make(__('app.tuoi'), 'tuoi'),
                Radio::make(__('app.gioitinh'), 'gioitinh')->options(Directory::gioitinh())->default(1),
                Text::make(__('app.quoctich'), 'quoctich'),
                Integer::make(__('app.sohochieu'), 'sohochieu'),
                Text::make(__('app.dienthoai'), 'dienthoai')->withMeta(['type' => 'phone']),
                Email::make(__('app.email'), 'email')->withMeta(['type' => 'email']),
            ])->map(fn($f) => $f->hideFromIndex()))),

            (new Panel(__('app.quanheParent'), [
                BelongsTo::make(__('app.cabenh'), 'quanheParent', 'App\Nova\Cabenh')->onlyOnDetail(),
            ])),

            BelongsToMany::make(__('app.quanheLinks'), 'quanheLinks', 'App\Nova\Cabenh')
                ->fields(function () {
                    return [
                        Froala::make(__('app.ghichu'), 'ghichu')->withFiles('uploads'),
                    ];
                })->searchable()->withSubtitles(),

            BelongsToMany::make(__('app.quanhes'), 'quanhes', 'App\Nova\Cabenh')
                ->fields(function () {
                    return [
                        Froala::make(__('app.ghichu'), 'ghichu')->withFiles('uploads'),
                    ];
                })->searchable()->withSubtitles(),

            (new Tabs('Dịch tễ', [
                __('app.laynhiems') => [
                    HasMany::make(__('app.laynhiems'), 'laynhiems', \App\Nova\Laynhiem::class),
                ],
                __('app.hanhtrinh') => [
                    Flexible::make('Hành trình', 'hanhtrinhs')
                        ->preset(\App\Nova\Flexible\Presets\HanhtrinhPagePreset::class)
                ],
                __('app.cachly') => [
                    Flexible::make($request->isResourceDetailRequest() ? null : __('app.cachly'), 'cachly')
                        ->preset(\App\Nova\Flexible\Presets\CachlyPagePreset::class)
                ],
                __('app.noi_pt') => [
                    Multiselect::make(__('app.noi_pt'), 'phongtoa_id')->singleSelect()->asyncResource(\App\Nova\PhongtoaPt::class)->nullable()->hideFromIndex(),
                ],
                __('app.dieutri') => [
                    Flexible::make($dieutri_label = $request->isResourceDetailRequest() ? null : __('app.dieutri'), 'dieutri')
                        ->preset(\App\Nova\Flexible\Presets\DieutriPagePreset::class)
                ],
                __('app.xetnghiems') => [
                    Flexible::make($request->isResourceDetailRequest() ? null : __('app.xetnghiems'), 'xetnghiems')
                        ->preset(\App\Nova\Flexible\Presets\XetnghiemPagePreset::class)
                ],
            ]))->showTitle(),
        ];
    }

    public static function afterSave(Request $request, \App\Models\Cabenh $model)
    {
        if (!$model->originalIsEquivalent('phanloai_cl')) {
            $index = static::$dirs['phanloai_cl']->search($model->phanloai_cl);
            $children = Quanhe::descendantsOf($model->id)->toTree();
            static::updatePhanloaiCl($children, $index + 1);

            if ($model->phanloai_cl === 'F0') Helper::updateCountF0();
        }

    }

    public static function afterCreate(Request $request, $model)
    {
        if ($model->phanloai_cl === 'F0') Helper::updateCountF0();
    }


    protected static function updatePhanloaiCl($quanhes, $index)
    {
        $phanloai_cl = collect(Directory::phanloai_cl())->values();
        foreach ($quanhes as $qh) {
            $qh->cabenh->phanloai_cl = static::$dirs['phanloai_cl']->get($index);
            $qh->cabenh->save();
            if ($qh->children->isNotEmpty()) {
                static::updatePhanloaiCl($qh->children, $index + 1);
            }
        }
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
            (new NovaDetachedFilters([
                (new PhanloaiClFilter)->withMeta(['width' => 'w-1/3']),
                (new NoiPhFilter)->withMeta(['width' => 'w-1/3']),
                (new DateRangeFilter)->setName(__('app.ngay_ntt'))->attribute('ngay_ntt')->withMeta(['width' => 'w-1/3']),
            ]))->width('full'),
            new FiltersSummary,
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
        $filters = [];

        if(!hc_auth()->isPhuong()) $filters[] = (new DependentFilter(__('app.px'), 'dc_maphuong'))->withOptions(Directory::phuongs());

        return $filters;
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
        return [
            (new ImportCabenh)->canSeeWhen(CabenhPolicy::$key.'.import'),
            (new ExportExcel(['serial', 'ngay_ntt', 'ma_qg', 'ma_kv', 'hoten', 'dc_diachi', 'phuong', 'yeuto_dt']))->canSeeWhen(CabenhPolicy::$key.'.export')
        ];
    }
}
