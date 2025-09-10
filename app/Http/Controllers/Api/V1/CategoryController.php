<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Categories\CreateCategoryData;
use App\DTOs\Categories\UpdateCategoryData;
use App\Entities\Models\Category;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\CreateRequests\CategoryCreateRequest;
use App\Http\Requests\Api\V1\UpdateRequests\CategoryUpdateRequest;
use App\Services\CategoryService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseApiController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        return $this->successResponse($categories);
    }

    public function store(CategoryCreateRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryService->createCategory(CreateCategoryData::fromRequest($request));

            return $this->createdResponse($category);
        } catch (AuthorizationException $e) {
            return $this->forbiddenResponse($e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            return $this->successResponse($category);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse($e->getMessage());
        }
    }

    public function update(CategoryUpdateRequest $request): JsonResponse
    {
        try {

            $updatedCategory = $this->categoryService->updateCategory(
                UpdateCategoryData::fromRequest($request)
            );

            return $this->successResponse($updatedCategory);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Category not found to be updated');
        } catch (AuthorizationException $e) {
            return $this->forbiddenResponse($e->getMessage());
        }
    }

    public function destroy(Category $category): JsonResponse
    {
        try {
            $deleted = $this->categoryService->deleteCategory($category);

            if (! $deleted) {
                return $this->errorResponse('Failed to delete category', 500);
            }

            return $this->noContentResponse();

        } catch (AuthorizationException $e) {
            return $this->forbiddenResponse($e->getMessage());
        }
    }
}
