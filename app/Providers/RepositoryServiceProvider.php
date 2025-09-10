<?php

namespace App\Providers;


use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\CategoryRepository;


use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
    ];
}
