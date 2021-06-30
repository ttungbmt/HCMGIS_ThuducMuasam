<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(\App\Support\HcAuth::class, function ($app) {
            $user = auth()->user();
            if($user->hasRole('phuong-editor')) return new \App\Support\HcAuthPhuong($user);
            if($user->hasRole('quan-editor')) return new \App\Support\HcAuthQuan($user);
            return new \App\Support\HcAuth($user);
        });
    }
}
