<?php
namespace App\Entities\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


use App\Entities\Models\Order;
use App\Entities\Models\Product;


trait OrderItemRelations
{

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
