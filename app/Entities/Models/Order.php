<?php

namespace App\Entities\Models;

use App\Entities\Enums\OrderStatus;
use App\Entities\Relations\OrderRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, OrderRelations;

    protected $fillable = [
        'customer_id',
        'status',
        'total_amount',
        'shipping_address',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'status' => OrderStatus::class,
        'total_amount' => 'decimal:2',
        'shipping_address' => 'string',
    ];
}
