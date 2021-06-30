<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cabenh;
use App\Support\Directory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThongkeController extends Controller
{
    public function pxF0(){
        $data = DB::table('v_tk_px_cabenh')
            ->orderBy('count', 'desc')
            ->orderBy('today_count', 'desc')
            ->get(['tenphuong', 'count', 'today_count']);
        return [
            'status' => 'OK',
            'data' => collect($data)->map(function ($v){
                $v->tenphuong = Str::of($v->tenphuong)
                    ->replaceFirst('Phường', 'P.')
                    ->replaceFirst('Xã', 'X.')
                ;
                return $v;
            })
        ];
    }

    public function nguon_ln(){
        $data = Cabenh::selectRaw('nguon_ln AS label, COUNT (*) AS value')->whereNotNull('nguon_ln')->groupBy('nguon_ln')->orderBy('value', 'DESC')->get();

        return [
            'status' => 'OK',
            'data' => $data
        ];
    }

    public function noi_ph_by_px(){
        $data = DB::table('cabenh', 'cb')->selectRaw('dc_maphuong maphuong, tenphuong, noi_ph, COUNT (*)')
            ->leftJoin(DB::raw('hc_phuong AS px'), 'px.maphuong', '=', 'cb.dc_maphuong')
            ->whereNotNull('noi_ph')
            ->groupByRaw('noi_ph, dc_maphuong, tenphuong')
            ->orderBy('tenphuong')->get();

        $cols = collect(Directory::noi_ph())->values()->mapWithKeys(fn($v) => [Str::slug($v, '_') => $v]);

        return [
            'status' => 'OK',
            'data' => $data->groupBy('maphuong')->map(function ($i) use($cols){
                return array_merge(['category' => data_get($i, '0.tenphuong')], $cols->mapWithKeys(fn($v, $k) => [$k => data_get($i->firstWhere('noi_ph', $v), 'count', 0)])->all());
            })->sortBy('category')->values(),
            'meta' => [
                'series' => $cols
            ]
        ];
    }
}
