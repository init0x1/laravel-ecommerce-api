<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Categories\CreateCategoryData;
use App\DTOs\Categories\UpdateCategoryData;
use App\Entities\Models\Category;
use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected $allowedFilters = [
        'name',
        'description',
    ];

    protected $allowedFiltersExact = [
        'id',
    ];

    protected $allowedRelationSort = [];

    protected $allowedFilterScopes = [];

    protected $allowedFields = [];

    protected $allowedAppends = [];

    protected $allowedSorts = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    protected $allowedDefaultSorts = [
        'id',
    ];

    public function model()
    {
        return Category::class;
    }

    public function create(CreateCategoryData $data): Category
    {
        return $this->model->create($data->toArray());
    }

    public function findById(int $id): ?Category
    {
        return $this->model->find($id);
    }

    public function update(UpdateCategoryData $data): Category
    {

        $category = $this->model->findOrFail($data->id);

        $category->update($data->toArray());

        return $category;

    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}
