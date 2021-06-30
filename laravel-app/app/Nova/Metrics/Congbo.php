<?php

namespace App\Nova\Metrics;

use App\Models\Cabenh;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class Congbo extends Partition
{
    public function name()
    {
        return __('app.congbo');
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $congbo_tb = DB::table('cabenh')->selectRaw('CASE WHEN ngay_cb IS NOT NULL THEN 1 ELSE 0 END AS congbo')->where('phanloai_cl', 'F0');
        $data = DB::table('cabenh')->fromSub($congbo_tb, 'cb')->selectRaw('congbo, count(congbo)')->groupBy('congbo')
            ->get();



        return $this->result([
            'Đã công bố' => optional($data->firstWhere('congbo', 1))->count,
            'Chờ công bố' => optional($data->firstWhere('congbo', 0))->count,
        ]);
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
        return 'congbo';
    }
}
