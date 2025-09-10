<?php

namespace App\Entities\Relations;

use App\Entities\Models\Order;
use App\Entities\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelations
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
