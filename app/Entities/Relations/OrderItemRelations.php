<?php

namespace App\Entities\Relations;

use App\Entities\Models\Order;
use App\Entities\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
