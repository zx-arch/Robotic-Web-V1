<?php

// app/Providers/RepositoryServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ActivityRepository;
use App\Repositories\IpGlobalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Tambahkan binding di RepositoryServiceProvider ketika menambah class 
         * repository atau interface

         * Note: Add
         * IpGlobalRepository::class,
         * Jika tidak menggunakan interface
         */

        $repositories = [ActivityRepository::class, IpGlobalRepository::class];

        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }
}