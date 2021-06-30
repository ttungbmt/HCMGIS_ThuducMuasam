<?php

namespace App\Nova\Flexible\Presets;

use Larabase\Nova\Flexible\Resolvers\PageResolver;
use Larabase\NovaFields\Text;
use Laravel\Nova\Fields\DateTime;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class HanhtrinhPagePreset extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        $field->resolver(PageResolver::class);

        $field->addLayout('Di chuyển nội địa', 'dichuyen_nd', [
            Text::make(__('app.phuongtien'), 'phuongtien')->rules('required'),
            Text::make(__('app.noidi'), 'noidi'),
            Text::make(__('app.diemdi'), 'diemdi'),
            Text::make(__('app.noiquacanh'), 'noiquacanh'),
            Text::make(__('app.noiden'), 'noiden'),
            Text::make(__('app.diemden'), 'diemden'),
            Text::make(__('app.sohieu'), 'sohieu'),
            Text::make(__('app.soghe'), 'soghe'),
            DateTime::make(__('app.thgian_khoihanh'), 'thgian_khoihanh')->nullable(),
            DateTime::make(__('app.thgian_den'), 'thgian_den')->nullable(),
        ]);

        $field->button('Thêm mới');
        $field->collapsed();
    }

}
