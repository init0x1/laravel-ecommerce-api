<?php

namespace App\Entities\Relations;

use App\Entities\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CategoryRelations
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
