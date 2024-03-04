<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CrudService
{
    /**
     * @var mixed $repository
     */
    protected mixed $repository;

    /**
     * @param array $attributes
     *
     * @return Paginator
     */
    public function index(array $attributes): Paginator
    {
        return $this->repository->getPaginatedList($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->repository->store($attributes);
    }

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return Model
     * @throws Throwable
     */
    public function update(Model $model, array $attributes): Model
    {
        /**@var Model $model */
        $model =  $this->repository->updateOrFail($model, $attributes);

        return $model;
    }

    /**
     * @param Model $model
     *
     * @return EventModel
     * @throws Throwable
     */
    public function delete(Model $model): Model
    {
        return $this->repository->destroy($model);
    }

    /**
     * @param Model $model
     * @param string $fieldName
     * @param mixed $data
     *
     * @return Model
     */
    public function patch(Model $model, string $fieldName, mixed $data): Model
    {
        return $this->repository->patch($model, $fieldName, $data);
    }
}
