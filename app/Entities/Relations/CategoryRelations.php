<?php
namespace App\Entities\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Entities\Models\Product;

trait CategoryRelations
{

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
