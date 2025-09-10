<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\DTOs\Products\CreateProductData;
use App\DTOs\Products\UpdateProductData;
use App\Entities\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{

    protected $allowedIncludes = [
        'stock',
    ];
    protected $allowedFilters = [
        'name',
        'description',
    ];

    protected $allowedFiltersExact = [
        'id',
        'category_id',
        'seller_id',
    ];

    protected $allowedRelationSort = [

    ];

    protected $allowedFilterScopes = [];

    protected $allowedFields = [];

    protected $allowedAppends = [];

    protected $allowedSorts = [
        'id',
        'name',
        'unit_price',
        'created_at',
        'updated_at',
    ];

    protected $allowedDefaultSorts = [
        'id',
    ];

    public function model()
    {
        return Product::class;
    }

    public function create(CreateProductData $data): Product
    {
        return $this->model->create($data->toArray());
    }


    public function findById(int $id): Product
    {
        return $this->model->find($id);
    }

    public function update(UpdateProductData $data): Product
    {
        $product = $this->model->findOrFail($data->id);
        $product->update($data->toArray());
        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
