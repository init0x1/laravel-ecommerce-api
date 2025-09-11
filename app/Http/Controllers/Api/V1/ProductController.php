<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Products\CreateProductData;
use App\DTOs\Products\UpdateProductData;
use App\Entities\Models\Product;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\CreateRequests\ProductCreateRequest;
use App\Http\Requests\Api\V1\UpdateRequests\ProductUpdateRequest;
use App\Services\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseApiController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        return $this->successResponse($products);
    }

    public function store(ProductCreateRequest $request): JsonResponse
    {
        try {

            $product = $this->productService->createProduct(CreateProductData::fromRequest($request));
            $product->load('stock');

            return $this->createdResponse($product);
        } catch (AuthorizationException $e) {
            return $this->forbiddenResponse($e->getMessage());
        }

    }

    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);
            $product->load('stock');

            return $this->successResponse($product);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse($e->getMessage());
        }
    }

    public function update(ProductUpdateRequest $request): JsonResponse
    {
        try {
            $data = UpdateProductData::fromRequest($request);
            $updatedProduct = $this->productService->updateProduct($data);
            $updatedProduct->load('stock');

            return $this->successResponse($updatedProduct);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Product not found to be updated');
        } catch (AuthorizationException $e) {
            return $this->forbiddenResponse($e->getMessage());
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $deleted = $this->productService->deleteProduct($product);

            if (! $deleted) {
                return $this->errorResponse('Failed to delete product', 500);
            }

            return $this->noContentResponse();
        } catch (AuthorizationException $e) {
            return $this->forbiddenResponse($e->getMessage());

        }
    }
}
