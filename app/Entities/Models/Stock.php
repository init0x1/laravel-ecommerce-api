<?php

namespace App\Entities\Models;

use App\Entities\Relations\StockRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
