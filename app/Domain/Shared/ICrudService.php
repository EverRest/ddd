<?php

namespace App\Domain\Shared;

use App\Infrastructure\Laravel\Model\EventModel;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Throwable;

interface ICrudService
{
    /**
     * @param array $attributes
     *
     * @return Paginator
     */
    public function index(array $attributes): Paginator;

    /**
     * @param array $attributes
     *
     * @return EventModel
     * @throws Exception
     */
    public function create(array $attributes): Model;

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return Model
     * @throws Throwable
     */
    public function update(Model $model, array $attributes): Model;

    /**
     * @param Model $model
     *
     * @return EventModel
     * @throws Throwable
     */
    public function delete(Model $model,): Model;

    /**
     * Patch and refresh model
     *
     * @param Model $model
     * @param string $fieldName
     * @param mixed $data
     *
     * @return Model
     */
    public function patch(Model $model, string $fieldName, mixed $data): Model;
}
