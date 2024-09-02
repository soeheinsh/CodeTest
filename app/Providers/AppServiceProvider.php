<?php

namespace App\Providers;

use App\Services\InternetServiceProvider\InternetServiceProviderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Services\InternetServiceProvider\Mpt;
use App\Services\InternetServiceProvider\Ooredoo;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            InternetServiceProviderInterface::class,
            'App\Services\InternetServiceProvider\\'.ucfirst(Request::capture()->segment(2))
        );

        $this->app->bind(Mpt::class, function ($app) {
            return new Mpt();
        });

        $this->app->bind(Ooredoo::class, function ($app) {
            return new Ooredoo();
        });
    }

    public function boot()
    {
        //
    }
}
