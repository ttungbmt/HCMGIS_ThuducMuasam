<?php
namespace App\Nova\Filters;

use App\Support\Directory;
use Illuminate\Http\Request;
use Larabase\Nova\Filters\MultiSelectFilter;

class PhanloaiClFilter extends MultiSelectFilter
{
    public $attribute = 'phanloai_cl';

    public function name()
    {
        return __('app.phanloai_cl');
    }

    public function options(Request $request)
    {
        return Directory::phanloai_cl();
    }
}
