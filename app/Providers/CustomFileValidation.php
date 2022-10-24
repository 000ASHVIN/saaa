<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Input;
use Validator;

class CustomFileValidation extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('upload_count', function($attribute, $value, $parameters)
        {
            $files = Input::file($parameters[0]);
            return (count($files) <= $parameters[1]) ? true : false;
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
