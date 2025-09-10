<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\DTOs\Stocks\UpdateStockData;
use App\Entities\Models\Stock;
use App\Http\Requests\Api\V1\UpdateRequests\StockUpdateRequest;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends BaseApiController
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(): JsonResponse
    {
        $stocks = $this->stockService->getAllStocks();
        return $this->successResponse($stocks);
    }


    public function show(int $id): JsonResponse
    {
        $stock = $this->stockService->getStockById($id);

        if (!$stock) {
            return $this->notFoundResponse('Stock not found');
        }

        $stock->load('product');

        return $this->successResponse($stock);
    }


    public function update(StockUpdateRequest $request, Stock $stock): JsonResponse
    {
        $data = UpdateStockData::fromRequest($request);
        $updatedStock = $this->stockService->updateStock($data);

        $updatedStock->load('product');

        return $this->successResponse($updatedStock);
    }
}
