<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Support\Directory;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function nearby(){
        $danhmuc = [
            'CHTL' => 'Cửa hàng tiện lợi',
            'CHỢ' => 'Chợ',
            'Siêu thị' => 'Siêu thị',
        ];

        $location = collect(explode(',',request()->input('location')))->reverse()->join(' ');
        $point = "SRID=4326;POINT({$location})";
        $query = DB::table('cuahang_cchh')
            ->selectRaw("ten_ch, diachi, loaihinh, tuyen_cc, tenphuong, tg_hoatdong, dienthoai, ht_giao_tt, tt_lienhe, ghichu, ST_AsGeoJSON(geom) geometry, ST_Distance ( geom::geography, '{$point}'::geography ) AS distance")
            ->where('status', '<>', 0)
            ->orderByRaw("geom <-> '{$point}'::geometry ")->limit(20);

        $data = $query->get()->map(function ($i) use($danhmuc){
            $i->geometry = json_decode($i->geometry, true);

            if($i->distance > 1000) $i->distance = number_format($i->distance/1000, 2). "km";
            else $i->distance = number_format($i->distance, 0). "m";

            $i->loaihinh = $i->loaihinh ? data_get($danhmuc, $i->loaihinh) : null;

            return $i;
        });

        return [
            'status' => 'OK',
            'data' => $data,
            'meta' => [

            ]
        ];
    }
}
