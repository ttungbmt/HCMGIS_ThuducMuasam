<?php

namespace App\Nova\Flexible\Presets;

use App\Models\Diadiem;
use Larabase\Nova\Flexible\Resolvers\PageResolver;
use App\Support\Directory;
use Larabase\NovaFields\Date;
use Larabase\NovaFields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class DieutriPagePreset extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        $noi_dts = Diadiem::whereHas('tags', fn ($query) =>  $query->whereIn('tags.id', [7]))->get(['id', 'ten'])->pluck('ten', 'id');

        $field->resolver(PageResolver::class);

        $field->addLayout(__('app.dieutri'), 'dieutri', [
            Select::make(__('app.noi_dt'), 'noi_dt_id')->options($noi_dts)->displayUsingLabels(),
//            Text::make(__('app.noi_dt'), 'noi_dt_id')->displayUsing(function ($value) use($noi_dts){
//                $label = data_get($noi_dts, $value);
//                return '<a href="/nova/resources/diadiems/'.$value.'" class="no-underline dim text-primary font-bold" target="_blank">'.$label.'</a>';
//            })->asHtml()->exceptOnForms(),
            Text::make(__('app.lydo_vv'), 'lydo_vv'),
            Date::make(__('app.ngay_vv'), 'ngay_vv'),
            Date::make(__('app.ngay_rv'), 'ngay_rv'),
            Select::make(__('app.tinhtrang_ht'), 'tinhtrang_ht')->options(Directory::tinhtrang_ht())->displayUsingLabels(),
            Textarea::make(__('app.ghichu'), 'ghichu'),
        ]);

        $field->button('Thêm mới');
        $field->collapsed();
        $field->limit(1);
        $field->sizeOnDetail('w-full')->removeBottomBorderOnDetail();
    }

}
