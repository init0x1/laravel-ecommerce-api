<?php

namespace App\Services;

use App\DTOs\Stocks\CreateStockData;
use App\DTOs\Stocks\UpdateStockData;
use App\Entities\Models\Stock;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Policies\V1\StockPolicy;
class StockService extends BaseService
{

    protected $stockRepository;

    protected $policyClass = StockPolicy::class;
    public function __construct(StockRepositoryInterface $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function createStock(CreateStockData $stockData): Stock
    {
        return $this->stockRepository->create($stockData);
    }

    public function getAllStocks(): LengthAwarePaginator
    {
        return $this->stockRepository
            ->applyQuery()
            ->paginate();
    }

    public function getStockById(int $id): ?Stock
    {
        return $this->stockRepository->findById($id);
    }

    public function updateStock(UpdateStockData $stockData): Stock
    {
        if (! $this->isAble('update', Stock::class)) {
            throw new AuthorizationException('You do not have permission to update stock.');
        }
        return $this->stockRepository->update($stockData);
    }

    public function deleteStock(Stock $stock): bool
    {
        return $this->stockRepository->delete($stock);
    }
}
