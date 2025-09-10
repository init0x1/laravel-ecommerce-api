<?php

namespace App\Entities\Relations;

use App\Entities\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait StockRelations
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
