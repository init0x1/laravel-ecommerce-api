<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Orders\CreateOrderData;
use App\DTOs\Orders\UpdateOrderData;
use App\Entities\Models\Order;
use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $allowedIncludes = [
        'customer',
        'orderItems',
        'orderItems.product',
    ];

    protected $allowedFilters = [
        'shipping_address',
    ];

    protected $allowedFiltersExact = [
        'id',
        'customer_id',
        'status',
    ];

    protected $allowedSorts = [
        'id',
        'created_at',
        'updated_at',
        'total_amount',
    ];

    protected $allowedDefaultSorts = [
        '-created_at',
    ];

    public function model(): string
    {
        return Order::class;
    }

    public function create(CreateOrderData $data): Order
    {
        return $this->model->create($data->toArray());
    }

    public function update(Order $order, UpdateOrderData $data): Order
    {
        $order->update($data->toArray());

        return $order->fresh();
    }
}
