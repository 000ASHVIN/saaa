<?php

namespace App\Providers;

use App\Blog\Category;
use App\Http\ViewComposers\NewsComposer;
use Illuminate\Support\ServiceProvider;

class NewsCatergoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('blog.includes.sidebar', NewsComposer::class);
        view()->composer('blog.index', NewsComposer::class);
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
