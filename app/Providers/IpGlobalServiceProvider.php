<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\IpGlobalService;

class IpGlobalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IpGlobalService::class, function ($app) {
            return new IpGlobalService($app->make(\App\Repositories\IpGlobalRepository::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}