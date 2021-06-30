<?php
namespace App\Nova\Filters;

use App\Support\Directory;
use Illuminate\Http\Request;
use Larabase\Nova\Filters\MultiSelectFilter;

class LoaihinhCHFilter extends MultiSelectFilter
{
    public $attribute = 'loaihinh';

    public function name()
    {
        return __('app.loaihinh');
    }

    public function options(Request $request)
    {
        return Directory::loaihinh_ch();
    }
}
