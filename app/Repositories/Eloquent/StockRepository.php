<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Contracts\StockRepositoryInterface;
use App\DTOs\Stocks\CreateStockData;
use App\DTOs\Stocks\UpdateStockData;
use App\Entities\Models\Stock;


class StockRepository extends BaseRepository implements StockRepositoryInterface
{
    protected $allowedIncludes = [
        'product',
    ];
    protected $allowedFilters = [];

    protected $allowedFiltersExact = [
        'id',
        'product_id',
    ];

    protected $allowedRelationSort = [
        'product.name',
    ];

    protected $allowedFilterScopes = [];

    protected $allowedFields = [];

    protected $allowedAppends = [];

    protected $allowedSorts = [
        'id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    protected $allowedDefaultSorts = [
        'id',
    ];

    public function model()
    {
        return Stock::class;
    }

    public function create(CreateStockData $data): Stock
    {
        return $this->model->create($data->toArray());
    }


    public function findById(int $id): ?Stock
    {
        return $this->model->find($id);
    }


    public function update(UpdateStockData $data): Stock
    {
        $stock = $this->model->findOrFail($data->id);
        $stock->update($data->toArray());
        return $stock;
    }

    public function delete(Stock $stock): bool
    {
        return $stock->delete();
    }
}
