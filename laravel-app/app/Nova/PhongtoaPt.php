<?php
namespace App\Nova;
use App\Policies\PhongtoaPtPolicy;
use App\Support\Directory;
use Illuminate\Support\Carbon;
use Larabase\Nova\Actions\ExportExcel;
use App\Nova\Actions\ImportPhongtoaPt;
use Larabase\Nova\Filters\DateRangeFilter;
use Larabase\Nova\Filters\MultiSelectFilter;
use Larabase\Nova\Filters\PhuongFilter;
use App\Nova\Filters\TinhtrangPt;
use App\Support\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Larabase\Nova\Cards\FiltersSummary;
use Larabase\NovaFields\Date;
use Larabase\NovaFields\Serial;
use Larabase\NovaFields\Text;
use Larabase\NovaMap\Fields\Map;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use OptimistDigital\MultiselectField\Multiselect;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;

class PhongtoaPt extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PhongtoaPt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'diachi';

    public static $with = ['phuong'];

    public function title()
    {
        return $this->ten_dd ?? $this->diachi;
    }

    public function subtitle()
    {
        return $this->diachi;
    }

    public static function joinQuery($query){
        $ln_pt = DB::table(\App\Models\LanPtCc::table())->selectRaw('phongtoa_pt_id, ngay_pt, ngaygo_pt');
        $lns_count = DB::table(\App\Models\PhongtoaLns::table())->selectRaw('phongtoa_pt_id, count(phongtoa_pt_id) lns_count')->groupBy('phongtoa_pt_id');

        $query
            ->leftJoinSub($ln_pt, 'ln', 'ln.phongtoa_pt_id', '=', 'phongtoa_pt.id')
            ->leftJoinSub($lns_count, 'lns_c', 'lns_c.phongtoa_pt_id', '=', 'phongtoa_pt.id');

        return $query;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        hc_auth()->filterQuery($query);
        return static::joinQuery($query);
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return static::joinQuery($query);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'ten_dd', 'diachi',
    ];

    public static function label()
    {
        return __('app.phongtoa_pt');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $formatDate = fn ($str) => $str ? Carbon::createFromFormat('Y-m-d', $str) : $str;
        $hcTps = \App\Models\HcTp::all(['id', 'ma_tp', 'ten_tp']);

        return [
            Serial::make('STT', 'serial')->onlyOnExport(),

            ID::make(__('ID'), 'id')->sortable(),
            Map::make(__('Map'), 'geom')->onlyOnIndex(),
            Multiselect::make(__('app.loaihinh'), 'loaihinh')->options(Directory::loaihinh_dd())->singleSelect()->sortable(),

            new Panel(__('app.diachi'), [
                Map::make(__('Map'), 'geom')->hideFromIndex(),
                Text::make(__('app.ten_dd'), 'ten_dd')->limit($request->isResourceIndexRequest() ? 5 : 0)->tooltip(['content' => $this->ten_dd])->sortable(),
                Text::make(__('app.diachi'), 'diachi')->hideFromIndex()->showOnExport(),
                ...Helper::dynamicHcFields(),
                Text::make(__('app.khupho'), 'khupho'),
                Text::make(__('app.to_dp'), 'to_dp')->hideFromIndex(),
            ]),

            new Panel(__('app.phongtoa_pt'), [
                Date::make(__('app.ngay_pt'), 'ngay_pt')->displayUsing($formatDate)->exceptOnForms(),
                Date::make(__('app.ngaygo_pt'), 'ngaygo_pt')->displayUsing($formatDate)->exceptOnForms(),
            ]),

            new Panel(__('Other'), [
                Text::make(__('app.ng_lienhe'), 'ng_lienhe')->hideFromIndex(),
                Text::make(__('app.sdt_lienhe'), 'sdt_lienhe')->hideFromIndex(),
                Trix::make(__('app.ghichu'), 'ghichu')->withFiles('uploads'),
            ]),

            HasMany::make('Các lần phong tỏa', 'lans_pt', 'App\Nova\PhongtoaLns'),
            HasMany::make('Danh sách ca bệnh', 'cabenhs', 'App\Nova\Cabenh'),
        ];
    }

    public function fieldsForExport(Request $request){
        return [
            Serial::make('STT', 'serial')
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
            (new NovaDetachedFilters([
                (new MultiSelectFilter(__('app.loaihinh'), 'loaihinh'))->withOptions(Directory::loaihinh_dd())->withMeta(['width' => 'w-1/2']),
                (new TinhtrangPt(__('app.tinhtrang_pt'), 'tinhtrang_pt'))->withMeta(['width' => 'w-1/4']),
                (new DateRangeFilter)->setName(__('app.ngay_pt'))->attribute('ngay_pt')->withMeta(['width' => 'w-1/4']),
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

        if(!auth()->user()->hasRole('phuong-editor')) {
            $filters = [
                Helper::quanFilter(),
                new PhuongFilter,
            ];
        }

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
        return [

        ];
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
            (new ImportPhongtoaPt)->canSeeWhen(PhongtoaPtPolicy::$key.'.import'),
            (new ExportExcel([
                'serial',
                'ten_dd',
                'diachi',
                'phuong',
                'khupho',
                'ngay_pt',
                'ngaygo_pt',
            ]))->canSeeWhen(PhongtoaPtPolicy::$key.'.export')
        ];
    }
}
