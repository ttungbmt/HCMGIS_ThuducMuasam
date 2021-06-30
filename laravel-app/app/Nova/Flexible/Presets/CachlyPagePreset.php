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

class CachlyPagePreset extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        $noi_cls = Diadiem::whereHas('tags', fn ($query) =>  $query->whereIn('tags.id', [1, 2]))->get(['id', 'ten'])->pluck('ten', 'id');

        $field->resolver(PageResolver::class);

        $field->addLayout(__('app.cachly'), 'cachly', [
            Select::make(__('app.noi_cl'), 'noi_cl_id')->options($noi_cls)->displayUsingLabels()->help('Cơ sở cách ly tập trung, Cơ sở cách ly y tế'),
            Select::make(__('app.hinhthuc'), 'hinhthuc')->options(Directory::hinhthuc_cl())->displayUsingLabels()->nullable(),
            Date::make(__('app.ngay_bd'), 'ngay_bd'),
            Date::make(__('app.ngay_kt'), 'ngay_kt')->default(null),
            Text::make(__('app.phong'), 'phong'),
            Textarea::make(__('app.ghichu'), 'ghichu'),
        ]);

        $field->button('Thêm mới');
        $field->collapsed();
        $field->limit(1);
        $field->sizeOnDetail('w-full')->removeBottomBorderOnDetail();
    }

}
