<?php

namespace App\Nova\Metrics;

use App\Models\Diadiem;
use App\Support\Directory;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class LoaiDd extends Partition
{
    public function name()
    {
        return __('app.loai_dd');
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Diadiem::class, 'loai_dd')->label(fn ($value) => is_null($value) ? 'None': data_get(Directory::loai_dd(), $value));
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'loai-dd';
    }


}
