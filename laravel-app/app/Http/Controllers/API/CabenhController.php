<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cabenh;
use App\Models\HcPhuong;
use App\Models\LayNhiem;
use App\Models\RanhphuongPg;
use Larabase\Nova\Models\MapLayer;
use Illuminate\Support\Carbon;

class CabenhController extends Controller
{
    public function index()
    {
        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        $query = Cabenh::with(['phuong', 'quan'])
            ->orderByDesc('ngay_ntt')
            ->orderByDesc('ma_kv')
            ->whereNotNull('ngay_ntt')
            ->where('phanloai_cl', 'F0');

        if($from_date) $query->where('ngay_ntt', '>=', to_date($from_date));
        if($to_date) $query->where('ngay_ntt', '<=', to_date($to_date));

        return [
            'status' => 'OK',
            'data' => $query
                ->get()
                ->map(function ($v) {
                    $ngay_ntt = data_get($v, 'ngay_ntt');
                    $ngay_ntt = $ngay_ntt ? $ngay_ntt->format('d/m/Y') : null;

                    return [
                        'type' => 'Feature',
                        'geometry' => data_get($v, 'geom'),
                        'properties' => collect([
                            'id' => data_get($v, 'id'),
                            'ma_kv' => data_get($v, 'ma_kv'),
                            'phanloai_cl' => data_get($v, 'phanloai_cl'),
                            'hoten' => data_get($v, 'hoten'),
                            'yeuto_dt' => data_get($v, 'yeuto_dt'),
                            'ngay_ntt' => $ngay_ntt,
                            'date' => $ngay_ntt,
                            'ten_ln' => data_get($v, 'dc_ten'),
                            'diachi' => data_get($v, 'dc_diachi'),
                            'maquan' => data_get($v->quan, 'maquan'),
                            'tenquan' => data_get($v->quan, 'tenquan'),
                            'maphuong' => data_get($v->phuong, 'maphuong'),
                            'tenphuong' => data_get($v->phuong, 'tenphuong'),
                        ])->except(auth()->guest() ? [
                            'hoten', 'yeuto_dt'
                        ] : [])
                    ];
                }),
            'meta' => [
                'popup' => data_get(MapLayer::find(16), 'popup')
            ]
        ];
    }

    public function phuongs(){
        return [
            'status' => 'OK',
            'data' => RanhphuongPg::where('maquan', '769')->get()->map(fn ($model) => [
                'type' => 'Feature',
                'geometry' => $model->geom,
                'properties' => collect($model->toArray())->only([
                    'maphuong',
                    'maquan',
                    'tenphuong',
                    'tenquan',
                ])
            ]),
            'meta' => [
                'colors' => [
                    ['label' => '0-10', 'fillColor' => '#FFEDA0', 'value' => [0, 5]],
                    ['label' => '10-20', 'fillColor' => '#FEB24C', 'value' => [5, 10]],
                    ['label' => '20-50', 'fillColor' => '#FC4E2A', 'value' => [10, 15]],
                    ['label' => '50-100', 'fillColor' => '#BD0026', 'value' => [20, 30]],
                    ['label' => '100-200', 'fillColor' => '#800026', 'value' => [30, 40]],
                ]
            ]
        ];
    }
}
