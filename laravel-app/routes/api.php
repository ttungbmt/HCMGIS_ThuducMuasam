<?php

use App\Http\Controllers\API\CabenhController;
use App\Http\Controllers\API\CORSController;
use App\Http\Controllers\API\CuahangCCHHController;
use App\Http\Controllers\API\MapsController;
use App\Http\Controllers\API\PhongtoaPtController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\ThongkeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('search/nearby', [SearchController::class, 'nearby']);

Route::get('cabenhs', [CabenhController::class, 'index'])->middleware('web');
Route::get('cuahang_cchh', [CuahangCCHHController::class, 'index'])->middleware('web');
Route::get('cuahang_cchh/voronoi', [CuahangCCHHController::class, 'voronoi'])->middleware('web');

Route::get('thongke/px-f0', [ThongkeController::class, 'pxF0']);
Route::get('thongke/nguon_ln', [ThongkeController::class, 'nguon_ln']);
Route::get('thongke/noi_ph_by_px', [ThongkeController::class, 'noi_ph_by_px']);

Route::get('phuongs', [CabenhController::class, 'phuongs']);
Route::get('maps/builder', [MapsController::class, 'builder'])->middleware('web');
Route::get('maps/legend', [MapsController::class, 'legend']);

Route::get('phongtoa-pt', [PhongtoaPtController::class, 'index']);

Route::any('cors', CORSController::class);

Route::prefix('dir')->group(function(){
    Route::get('/hc-tp', function (){
        return \App\Models\HcTp::get(['ma_tp', 'ten_tp'])->map(fn($model) => ['value' => $model->ma_tp, 'display' => $model->ten_tp]);
    });

    Route::get('/hc-tp/{tp}/quans', function (\App\Models\HcTp $tp){
        return $tp->quans->map(fn($model) => ['value' => $model->maquan, 'display' => $model->tenquan]);
    });

    Route::get('/hc-tp-by-maquan/{tp}/quans', function ($tp){
        return \App\Models\HcQuan::where('ma_tp', $tp)->get()->map(fn($model) => ['value' => $model->maquan, 'display' => $model->tenquan]);
    });

    Route::get('/hc-quan-by-maquan/{maquan}/phuongs', function ($maquan){
        return \App\Models\HcQuan::where('maquan', $maquan)->firstOrFail()->phuongs->map(fn($model) => ['value' => $model->maphuong, 'display' => $model->tenphuong]);
    });
});

