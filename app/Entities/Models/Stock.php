<?php

namespace App\Entities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Relations\StockRelations;
class Stock extends Model
{
    /** @use HasFactory<\Database\Factories\StockFactory> */
    use HasFactory, StockRelations;

    protected $fillable = [
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'quantity' => 'integer',
    ];

}
