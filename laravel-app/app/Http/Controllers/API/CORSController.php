<?php
namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CORSController extends Controller
{
    public function __invoke()
    {
        $query1 = request()->except(['url', 'method']);
        $url = request()->input('url');
        $method = Str::lower(request()->input('method', 'GET'));
        $parts = parse_url($url);
        parse_str($parts['query'], $query0);
        $response = Http::{$method}($url, array_merge($query0, $query1));
        return $response->json();
    }
}
