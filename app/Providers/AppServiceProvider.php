<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $this->registerMiddlewareAliases();

        parent::boot();
    }
    protected function registerMiddlewareAliases(): void
    {
        // Daftarkan alias middleware custom kamu di sini
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('super_admin', \App\Http\Middleware\CheckSuperAdmin::class);
    }

    public function map()
    {
        $this->mapWebRoutes();
        // map lainnya
    }

    protected function mapWebRoutes()
    {
        Route::middleware(['web', 'auth', 'super_admin'])
            ->group(base_path('routes/web.php'));
    }
}
