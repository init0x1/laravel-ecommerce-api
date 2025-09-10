<?php

namespace App\Services;

use App\DTOs\Categories\CreateCategoryData;
use App\DTOs\Categories\UpdateCategoryData;
use App\Entities\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(CreateCategoryData $categoryData): Category
    {
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

        if (!$category) {
            throw new ModelNotFoundException("Category with ID {$id} not found");
        }

        return $category;
    }

    public function updateCategory(UpdateCategoryData $categoryData): Category
    {
        return $this->categoryRepository->update($categoryData);
    }

    public function deleteCategory(Category $category): bool
    {
        return $this->categoryRepository->delete($category);
    }
}
