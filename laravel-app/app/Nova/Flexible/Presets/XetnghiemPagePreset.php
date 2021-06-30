<?php

namespace App\Nova\Flexible\Presets;

use App\Models\Diadiem;
use Larabase\Nova\Flexible\Resolvers\PageResolver;
use App\Support\Directory;
use Larabase\NovaFields\Date;
use Larabase\NovaFields\Text;
use Laravel\Nova\Fields\Select;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class XetnghiemPagePreset extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        $noi_lms = Diadiem::whereHas('tags', fn ($query) =>  $query->where('tags.id', 6))->get(['id', 'ten'])->pluck('ten', 'id');
        $noi_xns = Diadiem::whereHas('tags', fn ($query) =>  $query->where('tags.id', 5))->get(['id', 'ten'])->pluck('ten', 'id');

        $field->resolver(PageResolver::class);
        $field->addLayout(__('app.xetnghiems'), 'xetnghiems', [
            Select::make(__('app.noi_lm'), 'noi_lm_id')->options($noi_lms)->displayUsingLabels()->nullable(),
            Select::make(__('app.noi_xn'), 'noi_xn_id')->options($noi_xns)->displayUsingLabels()->nullable(),
            Text::make(__('app.lydo_xn'), 'lydo_xn'),
            Date::make(__('app.ngay_lm'), 'ngay_lm'),
            Date::make(__('app.ngay_kq'), 'ngay_kq'),
            Select::make(__('app.ketqua_xn'), 'ketqua_xn')->options(Directory::ketqua_xn())->nullable()->displayUsingLabels(),
        ]);

        $field->button('Thêm mới');
        $field->collapsed();
        $field->sizeOnDetail('w-full')->removeBottomBorderOnDetail();
    }
}
