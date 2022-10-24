<?php

namespace App\Providers;

use App\AppEvents\Extra;
use App\Presenters\Presenter;
use Illuminate\Support\ServiceProvider;

class EventAddOnServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.event.includes.event-information', function($view)
        {
            $view->with('presenters', Presenter::all()->pluck('name', 'id'));
//            $view->with('extras', Extra::where('is_active', true)->get()->pluck('title', 'id'));
//            $view->with('dietary_requirements', Dietary::where('is_active', true)->get()->pluck('title', 'id'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
