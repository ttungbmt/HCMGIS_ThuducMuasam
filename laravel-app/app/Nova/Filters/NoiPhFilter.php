<?php
namespace App\Nova\Filters;

use App\Support\Directory;
use Illuminate\Http\Request;
use Larabase\Nova\Filters\MultiSelectFilter;

class NoiPhFilter extends MultiSelectFilter
{
    public $attribute = 'noi_ph';

    public function name()
    {
        return __('app.noi_ph');
    }

    public function options(Request $request)
    {
        return Directory::noi_ph();
    }
}
