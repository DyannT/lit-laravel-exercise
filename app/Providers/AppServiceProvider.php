<?php

namespace App\Providers;

use App\Repository\CommonRepository;
use App\Repository\CommonRepositoryInterface;
use App\Repository\User\APIPostRepository;
use App\Repository\User\APIPostRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CommonRepositoryInterface::class,
            CommonRepository::class
        );

        $this->app->bind(
            APIPostRepositoryInterface::class,
            APIPostRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
