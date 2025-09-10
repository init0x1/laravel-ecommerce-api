<?php

namespace App\Services;

use App\DTOs\Categories\CreateCategoryData;
use App\DTOs\Categories\UpdateCategoryData;
use App\Entities\Models\Category;
use App\Policies\V1\CategoryPolicy;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService extends BaseService
{
    protected $categoryRepository;

    protected $policyClass = CategoryPolicy::class;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(CreateCategoryData $categoryData): Category
    {
        if (! $this->isAble('store', Category::class)) {
            throw new AuthorizationException('You do not have permission to create a category.');
        }

        return $this->categoryRepository->create($categoryData);
    }

    public function getAllCategories(): LengthAwarePaginator
    {
        return $this->categoryRepository
            ->applyQuery()
            ->paginate();
    }

    public function getCategoryById(int $id): Category
    {
        $category = $this->categoryRepository->findById($id);

        if (! $category) {
            throw new ModelNotFoundException("Category with ID {$id} not found");
        }

        return $category;
    }

    public function updateCategory(UpdateCategoryData $categoryData): Category
    {
        if (! $this->isAble('update', Category::class)) {
            throw new AuthorizationException('You do not have permission to update a category.');
        }

        return $this->categoryRepository->update($categoryData);
    }

    public function deleteCategory(Category $category): bool
    {
        if (! $this->isAble('delete', Category::class)) {
            throw new AuthorizationException('You do not have permission to delete a category.');
        }

        return $this->categoryRepository->delete($category);
    }
}
