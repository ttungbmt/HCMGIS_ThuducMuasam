<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CuahangCCHH;
use Illuminate\Support\Facades\DB;

class CuahangCCHHController extends Controller
{
    public function index(){
        $data = CuahangCCHH::whereStatus(1)->get();

        return [
            'status' => 'OK',
            'data' => $data->map(function ($v){
                return [
                    'type' => 'Feature',
                    'geometry' => data_get($v, 'geom'),
                    'properties' => [
                        'id' => data_get($v, 'id'),
                        'ten_ch' => data_get($v, 'ten_ch'),
                        'diachi' => data_get($v, 'diachi'),
                        'tenphuong' => data_get($v, 'tenphuong'),
                        'tuyen_cc' => data_get($v, 'tuyen_cc'),
                        'ghichu' => data_get($v, 'ghichu'),
                    ],
                ];
            })
        ];
    }

    public function voronoi(){
        // https://blog.cleverelephant.ca/2018/06/polygon-splitting.html
        $data = DB::table('v_cuahang_vo')->get();

        return [
            'status' => 'OK',
            'data' => $data->map(fn ($v) => [
                'type' => 'Feature',
                'geometry' => json_decode($v->geom)
            ])
        ];
    }
}
