<?php

namespace App\Providers;

use App\Models\Location;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer(['layouts.public', 'layouts.admin', 'auth.login'], function ($view) {
            $data = $view->getData();
            if (!isset($data['settings'])) {
                $view->with('settings', SiteSetting::all()->pluck('value', 'key'));
            }
            if (!isset($data['locations'])) {
                $view->with('locations', Location::active()->ordered()->get());
            }
        });
    }
}
