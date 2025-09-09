<?php
namespace App\Entities\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Entities\Models\Product;
use App\Entities\Models\Order;
trait UserRelations
{

    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'customer_id');
    }

}
