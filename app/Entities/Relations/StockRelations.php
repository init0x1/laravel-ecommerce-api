<?php
namespace App\Entities\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Entities\Models\Product;

trait StockRelations
{

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
