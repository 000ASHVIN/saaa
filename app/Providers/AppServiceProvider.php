<?php

namespace App\Providers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

use Illuminate\Support\Facades\Validator;
 
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {       
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');
        //Handesk::setup(config('services.handesk.url'), config('services.handesk.token')); 
        if ($this->app->environment() == 'local')
            return $this->setLocale('en_US.UTF-8');

        return $this->setLocale('en_ZA.UTF-8');
    }

    protected function setLocale($locale)
    {
        setlocale(LC_MONETARY, $locale);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
        // $this->app->alias('bugsnag.logger', Log::class);
        // $this->app->alias('bugsnag.logger', LoggerInterface::class);
    }
}
