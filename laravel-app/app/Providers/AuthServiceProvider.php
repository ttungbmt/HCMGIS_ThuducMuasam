<?php

namespace App\Providers;

use App\Policies\NovaPagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Larabase\Nova\Models\Map::class => 'App\Policies\MapPolicy',
        \Larabase\Nova\Models\MapLayer::class => 'App\Policies\MapLayerPolicy',
        \Larabase\Nova\Models\MapService::class => 'App\Policies\MapServicePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('nova-page:view', [NovaPagePolicy::class, 'view']);
    }
}
