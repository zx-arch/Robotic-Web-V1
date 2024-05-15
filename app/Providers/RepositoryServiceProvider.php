<?php

// app/Providers/RepositoryServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ActivityRepositoryInterface;
use App\Repositories\DbActivityRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ActivityRepositoryInterface::class, DbActivityRepository::class);
    }
}