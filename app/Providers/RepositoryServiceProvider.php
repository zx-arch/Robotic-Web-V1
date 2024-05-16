<?php

// app/Providers/RepositoryServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ActivityRepositoryInterface;
use App\Interfaces\IpGlobalRepositoryInterface;
use App\Repositories\DbActivityRepository;
use App\Repositories\IpGlobalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Tambahkan binding di RepositoryServiceProvider ketika menambah class 
         * repository atau interface
         */

        $repositories = [
            ActivityRepositoryInterface::class => DbActivityRepository::class,
            IpGlobalRepositoryInterface::class => IpGlobalRepository::class,
            /**
             * Note: Add
             * IpGlobalRepository::class,
             * Jika tidak menggunakan interface
             */
        ];

        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }
}