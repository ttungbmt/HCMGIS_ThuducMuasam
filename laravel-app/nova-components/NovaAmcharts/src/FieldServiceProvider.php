<?php

namespace Larabase\NovaAmcharts;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('amcharts-core', 'https://cdn.amcharts.com/lib/4/core.js');
            Nova::script('amcharts-charts', 'https://cdn.amcharts.com/lib/4/charts.js');
            Nova::script('amcharts-forceDirected', 'https://cdn.amcharts.com/lib/4/plugins/forceDirected.js');
            Nova::script('amcharts-themes-animated', 'https://cdn.amcharts.com/lib/4/themes/animated.js');

            Nova::script('nova-amcharts', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-amcharts', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
