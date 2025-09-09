<?php

namespace App\Entities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Relations\ProductRelations;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, ProductRelations;

    protected $fillable = [
        "name",
        "description",
        "unit_price",
        "category_id",
        "seller_id",
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

}
