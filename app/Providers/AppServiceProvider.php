<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        if ($this->app->environment('production') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('company_settings')) {
                    $view->with('company', CompanySetting::getSettings());
                }
            } catch (Throwable $e) {
                // Evitar error 500 si la base de datos se está inicializando
                $view->with('company', null);
            }
        });
    }
}
