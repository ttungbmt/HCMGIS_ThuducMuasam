<?php

namespace App\Providers;

use App\Models\Cabenh;
use App\Models\CabenhCn;
use App\Models\PhongtoaPt;
use App\Models\QuanhePivot;
use App\Nova\Metrics\CabenhPerDay;
use App\Nova\Metrics\Congbo;
use App\Nova\Metrics\Hoiphuc;
use App\Nova\Metrics\NewUser;
use App\Nova\Metrics\PhanloaiCl;
use App\Nova\Metrics\XetnghiemPerDay;
use App\Observers\CabenhCnObserver;
use App\Observers\CabenhObserver;
use App\Observers\MenuItemObserver;
use App\Observers\PhongtoaPtObserver;
use App\Observers\QuanhePivotObserver;
use Illuminate\Support\Facades\Gate;
use Larabase\Nova\Resources\Map;
use Larabase\Nova\Resources\MapLayer;
use Larabase\Nova\Resources\MapService;
use Larabase\Nova\Support\MenuHelper;
use Larabase\NovaPage\NovaPage;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use OptimistDigital\MenuBuilder\Models\MenuItem;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    protected function resources()
    {
        parent::resources();

        Nova::resources([
            Map::class,
            MapLayer::class,
            MapService::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Cabenh::observe(CabenhObserver::class);
        PhongtoaPt::observe(PhongtoaPtObserver::class);
        QuanhePivot::observe(QuanhePivotObserver::class);
        MenuItem::observe(MenuItemObserver::class);
        CabenhCn::observe(CabenhCnObserver::class);

        app(\Larabase\Settings\General::class)->boot();

        NovaPage::addTemplate(\Larabase\Setting\Pages\GeneralSetting::class);
        NovaPage::addTemplate(\Larabase\Setting\Pages\AppearanceSetting::class);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new PhanloaiCl,
            new Congbo,
            new CabenhPerDay,
            new XetnghiemPerDay,
            new Hoiphuc,
            new NewUser,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        $tools = MenuHelper::getTools();

        $adminTools = collect([
            \Vyuldashev\NovaPermission\NovaPermissionTool::make()
                ->rolePolicy(\Larabase\Policies\RolePolicy::class)
                ->permissionPolicy(\Larabase\Policies\PermissionPolicy::class),
//            new \OptimistDigital\MenuBuilder\MenuBuilder,
            new \CodencoDev\NovaGridSystem\NovaGridSystem,
            new \Mirovit\NovaNotifications\NovaNotifications,
            new \ChrisWare\NovaBreadcrumbs\NovaBreadcrumbs,
        ]);

//        dd(auth()->user()->can('menu-builder'), $tools->merge($adminTools)->all());

        return $adminTools->merge($tools)->all();
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
