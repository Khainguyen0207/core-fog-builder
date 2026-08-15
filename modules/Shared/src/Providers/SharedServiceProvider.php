<?php

namespace Modules\Shared\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Registry\AllowAllRegistrationVisibility;
use Modules\Shared\Registry\Contracts\RegistrationVisibility;
use Modules\Shared\Tables\Factory\TableFactory;
use Modules\Shared\Tables\Registry\TableRegistry;
use Modules\Shared\View\NavbarUserPresenter;

class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/figure-admin-shared.php', 'figure-admin-shared');

        $this->app->singletonIf(RegistrationVisibility::class, AllowAllRegistrationVisibility::class);
        $this->app->singleton(
            TableRegistry::class,
            fn (Application $app): TableRegistry => new TableRegistry($app->make(RegistrationVisibility::class)),
        );
        $this->app->singleton(
            MenuRegistry::class,
            fn (Application $app): MenuRegistry => new MenuRegistry($app->make(RegistrationVisibility::class)),
        );
        $this->app->singleton(NavbarUserPresenter::class, fn (): NavbarUserPresenter => new NavbarUserPresenter);
        $this->app->singleton(
            TableFactory::class,
            fn (Application $app): TableFactory => new TableFactory(
                $app->make(TableRegistry::class),
                $app,
            ),
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'shared');

        if ((bool) config('figure-admin-shared.routes.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');
        }

        $menus = $this->app->make(MenuRegistry::class);
        $logViewerRoute = config('figure-admin-shared.menu.log_viewer_route');

        if (is_string($logViewerRoute) && $logViewerRoute !== '' && Route::has($logViewerRoute)) {
            $menus->register('shared.log-viewer', [
                'name' => 'Log Viewer',
                'icon' => 'menu-icon tf-icons bx bx-bug',
                'route' => $logViewerRoute,
                'active' => [$logViewerRoute],
            ], 1100);
        }

        view()->composer('shared::layouts.*', function ($view) use ($menus): void {
            $view->with('sharedMenuRegistry', $menus);
        });

        $this->publishes([
            __DIR__.'/../../config/figure-admin-shared.php' => config_path('figure-admin-shared.php'),
        ], 'figure-admin-shared-config');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/shared'),
        ], 'figure-admin-shared-views');
    }
}
