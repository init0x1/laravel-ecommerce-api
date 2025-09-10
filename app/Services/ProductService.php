<?php

namespace App\Services;

use App\DTOs\Products\CreateProductData;
use App\DTOs\Products\UpdateProductData;
use App\DTOs\Stocks\CreateStockData;
use App\Entities\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected $productRepository;
    protected $stockRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        StockRepositoryInterface $stockRepository
    ) {
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
    }

    public function createProduct(CreateProductData $productData): Product
    {
        return DB::transaction(function () use ($productData) {

            $product = $this->productRepository->create($productData);

        $stockData = CreateStockData::fromProduct($product, $productData->initial_quantity);

        $this->stockRepository->create($stockData);

            return $product;
        });
    }

    public function getAllProducts(): LengthAwarePaginator
    {
        return $this->productRepository
            ->applyQuery()
            ->paginate();
    }

    public function getProductById(int $id): ?Product
    {
        $product = $this->productRepository->findById($id);
        if(!$product){
            throw new ModelNotFoundException("Product with ID {$id} not found");
        }
        return $product;
    }

    public function updateProduct(UpdateProductData $productData): Product
    {
        return $this->productRepository->update($productData);
    }

    public function deleteProduct(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }
}
