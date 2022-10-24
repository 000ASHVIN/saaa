<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\SubscriptionRenewed::class => [
            \App\Listeners\GenerateSubscriptionInvoice::class,
        ],
        \App\Events\CourseSubscriptionRenewed::class => [
            \App\Listeners\GenerateCourseSubscriptionInvoice::class,
        ],
        \App\Events\CourseSubscibed::class => [
            \App\Listeners\CourseSubscribedListeners::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
