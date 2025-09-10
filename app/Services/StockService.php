<?php

namespace App\Services;

use App\DTOs\Stocks\CreateStockData;
use App\DTOs\Stocks\UpdateStockData;
use App\Entities\Models\Stock;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class StockService
{
    protected $stockRepository;

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
        return $this->stockRepository->update($stockData);
    }

    public function deleteStock(Stock $stock): bool
    {
        return $this->stockRepository->delete($stock);
    }
}
