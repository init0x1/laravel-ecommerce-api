<?php

namespace App\Repositories\BaseRepository;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository implements RepositoryInterface
{
    protected $app;

    protected $model;

    protected $allowedIncludes = [];

    protected $allowedFilters = [];

    protected $allowedFiltersExact = [];

    protected $allowedRelationSort = [];

    protected $allowedFilterScopes = [];

    protected $allowedFields = [];

    protected $allowedAppends = [];

    protected $allowedSorts = [];

    protected $allowedDefaultSorts = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract public function model();

    public function getModel()
    {
        return $this->model;
    }

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new \InvalidArgumentException(
                sprintf('Class %s must be an instance of %s', get_class($model), Model::class)
            );
        }

        return $this->model = $model;
    }

    public static function __callStatic($method, $arguments)
    {
        // return call_user_func_array([new static(), $method], $arguments);
        $instance = app(static::class);

        return call_user_func_array([$instance, $method], $arguments);
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }

    public function getAllowedIncludes(): array
    {
        return $this->allowedIncludes;
    }

    public function applyQuery(?array $params = null)
    {
        foreach ($this->allowedFiltersExact as $field) {
            array_push($this->allowedFilters, AllowedFilter::exact($field));
        }
        foreach ($this->allowedFilterScopes as $scope_filter) {
            array_push($this->allowedFilters, AllowedFilter::scope($scope_filter));
        }
        if ($this->model instanceof Builder) {
            $this->model = $this->initiateSpatieQueryBuilder($this->model, $params)->allowedFields($this->allowedFields)
                ->allowedFilters($this->allowedFilters)->allowedIncludes($this->allowedIncludes)
                ->allowedSorts($this->allowedSorts);
        } else {
            $query = app()->make($this->model())->newQuery();
            $this->model = $this->initiateSpatieQueryBuilder($query, $params)->allowedFields($this->allowedFields)->allowedFilters($this->allowedFilters)
                ->allowedIncludes($this->allowedIncludes)->allowedSorts($this->allowedSorts);
        }
        if (! empty($this->allowedDefaultSorts)) {
            $this->model = $this->model->defaultSorts($this->allowedDefaultSorts);
        }

        return $this;
    }

    public function getFiltersFromRequest()
    {
        $filters = request()->query('filter', []);

        $allowedFilters = array_filter($this->allowedFilters, function ($value) {
            return is_string($value);
        });

        $allowedFiltersExact = array_filter($this->allowedFiltersExact, function ($value) {
            return is_string($value);
        });

        $filters = array_intersect_key($filters, array_flip([
            ...$allowedFilters,
            ...$allowedFiltersExact,
        ]));

        return $filters;
    }

    private function initiateSpatieQueryBuilder($subject, ?array $query_params = null): QueryBuilder
    {
        $mergedParams = array_merge(request()->query(), $query_params ?? []);

        return QueryBuilder::for($subject, new Request($mergedParams));
    }
}
