<?php

namespace App\Providers;

use App\Http\ViewComposers\DiscountsComposer;
use App\Http\ViewComposers\ListingsComposer;
use App\Http\ViewComposers\StoreComposer;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\EventsComposer;
use App\Http\ViewComposers\SearchComposer;
use App\Http\ViewComposers\AuthUserComposer;
use App\Http\ViewComposers\UserCountComposer;

;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'includes.top-bar', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.includes.sidebar', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.general', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.company.create', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.company.pending', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.company.invite', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.edit.addresses', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.edit.privacy', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.company.index', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.webinars_on_demand', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.includes.CpdCycle.create_cycle', AuthUserComposer::class
        );

        view()->composer(
            'dashboard.cpd', AuthUserComposer::class
        );

        view()->composer(
            'partials.uce', EventsComposer::class
        );

        view()->composer(
            'includes.top-footer', EventsComposer::class
        );

        view()->composer(
            'dashboard.general', EventsComposer::class
        );

        view()->composer(
            'admin.members.search', SearchComposer::class
        );


        view()->composer(
            ['store.includes.sidebar'], StoreComposer::class
        );

        view()->composer(
            ['admin.store.products.includes.modals.add-to-listing'], ListingsComposer::class
        );

        view()->composer(
            ['admin.store.listings.includes.modals.add-discount'], DiscountsComposer::class
        );

        view()->composer(
            ['auth.login'], UserCountComposer::class
        );
        view()->composer(
            ['auth.registration.simple', 'auth.sub_events.registration'], UserCountComposer::class
        );
		
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
