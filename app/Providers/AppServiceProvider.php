<?php

namespace App\Providers;

use App\Services\InternetServiceProvider\InternetServiceProviderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Services\InternetServiceProvider\Mpt;
use App\Services\InternetServiceProvider\Ooredoo;
use App\Services\EmployeeManagement\EmployeeInterface;
use App\Services\EmployeeManagement\Staff;
use App\Services\EmployeeManagement\NonEmployeeInterface;
use App\Services\EmployeeManagement\Applicant;

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

        $this->app->bind(EmployeeInterface::class, Staff::class);

        $this->app->bind(NonEmployeeInterface::class, Applicant::class);
    }

    public function boot()
    {
        //
    }
}
