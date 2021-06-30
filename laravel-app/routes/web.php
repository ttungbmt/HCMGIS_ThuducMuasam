<?php

use App\Events\MyEvent;
use App\Exports\HcTp;
use App\Http\Controllers\API\MapsController;
use App\Imports\PhongtoaPtImport;
use App\Support\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Tags\Tag;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('/', function () {
//    return redirect(\Laravel\Nova\Nova::path());
//});

Route::get('/', function () {
    if(Str::contains($_SERVER['HTTP_HOST'], ['hcmgis.vn']) && !isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        return redirect('https://thuduc-covid.hcmgis.vn');
    };

    return File::get('vendor/maps/index.html');
});

Route::get('update-feature-count', [MapsController::class, 'updateFeatureCount']);

Route::get('/status', function () {
    $model = \App\Models\CuahangCCHH::find(1);
    dd($model->phantuyens->map->toArray());
});

Route::get('/welcome', function () {
    dd(\App\Support\Directory::noi_ph());
//    $models = \App\Models\Cabenh::where('phanloai_cl', 'F0')->get();
//    foreach ($models as $model){
//        $source = DB::table('nguon_ln')->where('ma_kv', $model->ma_kv)->first();
//
//        if($ma_kv = $model->ma_kv && $source){
//            $model->nguon_ln = $source->nguon_ln;
//            $model->save();
//        }
//    }

//    dd($model->map->toArray());
//    $hcTps = \App\Models\HcTp::all(['id', 'ma_tp', 'ten_tp']);
    return view('welcome');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
