<?php

namespace App\Providers;

use App\Entities\Models\Category;
use App\Entities\Models\Order;
use App\Entities\Models\Product;
use App\Entities\Models\Stock;
use App\Policies\V1\CategoryPolicy;
use App\Policies\V1\OrderPolicy;
use App\Policies\V1\ProductPolicy;
use App\Policies\V1\StockPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
        Stock::class => StockPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
