<?php

namespace App\Providers;


use App\Repositories\Contracts\UserRepositoryInterface;


use App\Repositories\Eloquent\UserRepository;

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

    ];
}
