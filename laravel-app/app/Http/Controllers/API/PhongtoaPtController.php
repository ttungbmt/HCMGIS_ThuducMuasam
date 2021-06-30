<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PhongtoaPt;
use App\Support\Directory;
use Carbon\Traits\Creator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Select;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PhongtoaPtController extends Controller
{
    public function index()
    {
        $columns = ['id', 'loaihinh', 'ten_dd', 'diachi', 'ma_tp', 'maquan', 'maphuong', 'to_dp', 'khupho', 'ng_lienhe', 'sdt_lienhe', 'created_at', 'updated_at',];

        $data = QueryBuilder::for(PhongtoaPt::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'loaihinh',
                'diachi',
                AllowedFilter::exact('ma_tp'),
                AllowedFilter::exact('maquan'),
                AllowedFilter::exact('maphuong'),
                AllowedFilter::partial('ten_tp', 'tp.ten_tp'),
                AllowedFilter::partial('tenphuong', 'phuong.tenphuong'),
                AllowedFilter::partial('tenquan', 'quan.tenquan'),
                'to_dp',
                'khupho',
                'ng_lienhe',
                'sdt_lienhe',
                AllowedFilter::scope('ngay_pt'),
                AllowedFilter::scope('ngay_pt_between'),
                AllowedFilter::scope('ngaygo_pt'),
                AllowedFilter::scope('ngay_pt_between'),
                AllowedFilter::scope('tinhtrang'),
            ])
            ->with([
                'lan_pt',
                'tp',
                'phuong',
                'quan'
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                'id', 'loaihinh', 'ten_dd', 'diachi', 'ma_tp', 'maquan', 'maphuong',
            ])
            ->get();


        $data = $data->map(function ($model) {
            $formatDate = fn($date) => $date ? (new Carbon($date))->format('d/m/Y') : null;
            $properties = collect([
                'id',
                'loaihinh',
                'ten_dd',
                'diachi',
                'ma_tp',
                'maquan',
                'maphuong',
                'ten_tp' => 'tp.ten_tp',
                'tenquan' => 'quan.tenquan',
                'tenphuong' => 'phuong.tenphuong',
                'to_dp',
                'khupho',
                'ngay_pt' => fn($model) => $formatDate(data_get($model->lan_pt, 'ngay_pt')),
                'ngaygo_pt' => fn($model) => $formatDate(data_get($model->lan_pt, 'ngaygo_pt')),
                'tinhtrang' => fn($model) => data_get($model->lan_pt, 'ngaygo_pt') ? 0 : 1,
                'ng_lienhe',
                'sdt_lienhe',
                'ghichu',
                'created_at',
                'updated_at',
            ])->mapWithKeys(function ($field, $key) use ($model) {
                if (is_numeric($key)) return [$field => data_get($model, $field)];
                if (is_string($field)) return [$key => data_get($model, $field)];
                if (is_callable($field)) return [$key => call_user_func($field, $model)];
                if($field instanceof Field){
                    if($field->component === 'select-field') return [$key => is_null($model->{$field->attribute}) ? null : data_get($field->meta['options'], $model->{$field->attribute}.'.label')];
                }
                return [$field => null];
            });

            return [
                'type' => 'Feature',
                'geometry' => data_get($model, 'geom'),
                'properties' => $properties
            ];
        });

        return $data;
    }
}
