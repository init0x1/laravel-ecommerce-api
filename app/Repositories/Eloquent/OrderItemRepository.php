<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Orders\OrderItemData;
use App\Entities\Models\OrderItem;
use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Contracts\OrderItemRepositoryInterface;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    protected $allowedIncludes = [
        'order',
        'product',
    ];

    protected $allowedFilters = [];

    protected $allowedFiltersExact = [
        'id',
        'order_id',
        'product_id',
    ];

    protected $allowedSorts = [
        'id',
        'created_at',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function model(): string
    {
        return OrderItem::class;
    }

    public function create(OrderItemData $data): OrderItem
    {
        return $this->model->create($data->toArray());
    }

    public function createMany(array $orderItemsData): bool
    {
        $items = collect($orderItemsData)->map(fn ($data) => $data->toArray())->toArray();

        return $this->model->insert($items);
    }
}
