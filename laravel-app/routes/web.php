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
    if(Str::contains($_SERVER['HTTP_HOST'], ['hcmgis.vn']) && data_get($_SERVER, 'HTTP_X_FORWARDED_PROTO') === 'http') {
        return redirect(env('APP_URL'));
    };

    return File::get('vendor/maps/index.html');
});

Route::get('update-feature-count', [MapsController::class, 'updateFeatureCount']);

Route::get('/status', function () {
});

Route::get('/welcome', function () {
    return view('welcome');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
