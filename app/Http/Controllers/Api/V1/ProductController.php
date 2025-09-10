<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\DTOs\Products\CreateProductData;
use App\DTOs\Products\UpdateProductData;
use App\Entities\Models\Product;
use App\Http\Requests\Api\V1\CreateRequests\ProductCreateRequest;
use App\Http\Requests\Api\V1\UpdateRequests\ProductUpdateRequest;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $product = $this->productService->createProduct(CreateProductData::fromRequest($request));
        $product->load('stock');
        return $this->createdResponse($product);
    }


    public function show(int $id): JsonResponse
    {

        $product = $this->productService->getProductById($id);
        if (!$product) {
            return $this->notFoundResponse('Product not found');
        }

        $product->load('stock');

        return $this->successResponse($product);
    }


    public function update(ProductUpdateRequest $request): JsonResponse
    {
        try{
            $data = UpdateProductData::fromRequest($request);
            $updatedProduct = $this->productService->updateProduct($data);
            $updatedProduct->load('stock');
            return $this->successResponse($updatedProduct);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Product not found to be updated');
        }

    }


    public function destroy(Product $product): JsonResponse
    {

        $deleted = $this->productService->deleteProduct($product);

        if (!$deleted) {
            return $this->errorResponse('Failed to delete product', 500);
        }

        return $this->noContentResponse();
    }
}
