<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use App\Domain\Shared\Enum\ListRequestEnum;
use GeneaLabs\LaravelModelCaching\CachedBuilder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as IlluminateCollection;
use Illuminate\Support\Facades\App;
use Throwable;

class Repository implements IRepository
{
    /**
     * @var string $default_order
     */
    protected string $default_order = 'DESC';

    /**
     * @var string $default_sort
     */
    protected string $default_sort = 'id';

    /**
     * @var int $default_limit
     */
    protected int $default_limit = 10;

    /**
     * @var string
     */
    protected string $model;

    /**
     * @var array
     */
    protected array $with = [];

    /**
     * Get a list of models
     *
     * @param array $data
     *
     * @return IlluminateCollection
     */
    public function getList(array $data = []): IlluminateCollection
    {
        $query = $this->listQuery($data);

        return $query->get();
    }

    /**
     * Get a list of models
     *
     * @param array $data
     *
     * @return Paginator
     */
    public function getPaginatedList(array $data = []): Paginator
    {
        $query = $this->listQuery($data);

        return $this->paginate($query, $data);
    }

    /**
     * @return EloquentBuilder|CachedBuilder
     */
    public function query()
    {
        /**
         * @var Model $model
         */
        $model = App::make($this->model);

        return $model::query()->lockForUpdate();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model::create($data)->refresh();
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function firstOrCreate(array $data): Model
    {
        return $this->model::firstOrCreate($data);
    }

    /**
     * Put and refresh model
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        $model->fill($data)->save();

        return $model->refresh();
    }

    /**
     * Patch and refresh model
     *
     * @param Model $model
     * @param string $fieldName
     * @param mixed $data
     *
     * @return Model
     */
    public function patch(Model $model, string $fieldName, mixed $data): Model
    {
        return $this->update($model, [$fieldName => $data]);
    }

    /**
     * Put or throw an exception if it fails.
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     * @throws Throwable
     */
    public function updateOrFail(Model $model, array $data): Model
    {
        $model->updateOrFail($data);
        return $model;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): Model
    {
        return $this->model::findOrFail($id);
    }

    /**
     * @return void
     */
    public function destroyAll(): void
    {
        in_array(SoftDeletes::class, $this->model()->getFillable(), true) ?
            $this->model::all()->each(fn(Model $model) => $model->delete()) :
            $this->model::truncate();
    }

    /**
     * Delete the model from the database within a transaction.
     *
     * @param Model $model
     * @param bool $force
     *
     * @return Model
     * @throws Throwable
     */
    public function destroy(Model $model, bool $force = false): Model
    {
        if ($force) {
            $model->forceDelete();
        } else {
            $model->deleteOrFail();
        }
        return $model;
    }


    /**
     * @param array $data
     *
     * @return mixed
     */
    protected function listQuery(array $data): mixed
    {
        $query = $this->search($data);
        $query = $this->with($query,);
        $this->filter(
            $query,
            Arr::except($data, ListRequestEnum::values())
        );
        $this->sort(
            $query,
            Arr::only(
                $data,
                [
                    ListRequestEnum::sortKey->value,
                    ListRequestEnum::orderKey->value,
                ]
            ),
        );

        return $query;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    protected function search(array $data): mixed
    {
        $model = $this->model();
        $searchableAttributes = $model->getFillable();
        $search = Arr::get($data, ListRequestEnum::searchKey->value, '');
        $query = $this->query();
        if ($search) {
            $query->where(
                function ($query) use ($searchableAttributes, $search) {
                    foreach ($searchableAttributes as $searchableAttribute) {
                        $query->orWhere($searchableAttribute, 'LIKE', "%$search%");
                    }
                }
            );
        }

        return $query;
    }

    /**
     * @param mixed $query
     * @param array $filter
     *
     * @return mixed
     */
    protected function filter(mixed $query, array $filter): mixed
    {
        $query->when($filter, fn($query) => $this->applyFilter($query, $filter));

        return $query;
    }

    /**
     * @param mixed $query
     * @param array $filter
     *
     * @return mixed
     */
    protected function applyFilter(mixed $query, array $filter): mixed
    {
        foreach ($filter as $filterKey => $filterValue) {
            if (is_array($filterValue)) {
                $query->whereIn($filterKey, $filterValue);
            } else {
                $query->where($filterKey, $filterValue);
            }
        }

        return $query;
    }

    /**
     * @param mixed $query
     * @param array $data
     *
     * @return Paginator
     */
    protected function paginate(mixed $query, array $data): Paginator
    {
        $limit = Arr::get($data, ListRequestEnum::limitKey->value) ?: $this->default_limit;

        return $query->paginate($limit);
    }

    /**
     * @param mixed $query
     * @param array $data
     *
     * @return mixed
     */
    protected function sort(mixed $query, array $data): mixed
    {
        $sort = $this->getSortColumn($data);
        $order = $this->getDirectionColumn($data);
        $query->when(
            $sort,
            fn($query) => $query->orderBy($sort, $order)
        );

        return $query;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getSortColumn(array $data): string
    {
        return Arr::get($data, ListRequestEnum::sortKey->value, $this->default_sort);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getDirectionColumn(array $data): string
    {
        return Arr::get($data, ListRequestEnum::orderKey->value, $this->default_order);
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    protected function with(mixed $query,): mixed
    {
        if (!empty($this->with)) {
            $query->with($this->with);
        }
        return $query;
    }

    /**
     * @return Model
     */
    protected function model(): Model
    {
        /** @var Model $model */
        $model = new $this->model();

        return $model;
    }
}
