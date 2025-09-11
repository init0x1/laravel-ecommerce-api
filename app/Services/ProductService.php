<?php

namespace App\Services;

use App\DTOs\Products\CreateProductData;
use App\DTOs\Products\UpdateProductData;
use App\DTOs\Stocks\CreateStockData;
use App\Entities\Models\Product;
use App\Policies\V1\ProductPolicy;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService extends BaseService
{
    protected $productRepository;

    protected $stockRepository;

    protected $policyClass = ProductPolicy::class;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        StockRepositoryInterface $stockRepository
    ) {
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
    }

    public function createProduct(CreateProductData $productData): Product
    {
        if (! $this->isAble('store', Product::class)) {
            throw new AuthorizationException('You do not have permission to create a product.');
        }

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
        if (! $product) {
            throw new ModelNotFoundException("Product with ID {$id} not found");
        }

        return $product;
    }

    public function updateProduct(UpdateProductData $productData): Product
    {
        if (! $this->isAble('update', Product::class)) {
            throw new AuthorizationException('You do not have permission to update a product.');
        }

        return $this->productRepository->update($productData);
    }

    public function deleteProduct(Product $product): bool
    {
        if (! $this->isAble('delete', Product::class)) {
            throw new AuthorizationException('You do not have permission to delete a product.');
        }

        return $this->productRepository->delete($product);
    }
}
