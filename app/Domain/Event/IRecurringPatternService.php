<?php

namespace App\Domain\Event;

use App\Infrastructure\Laravel\Model\EventModel;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use Illuminate\Database\Eloquent\Model;
use Throwable;

interface IRecurringPatternService
{
    /**
     * @param array $attributes
     *
     * @return RecurringPatternModel
     */
    public function create(array $attributes): RecurringPatternModel;

    /**
     * @param Model $model
     *
     * @return EventModel
     * @throws Throwable
     */
    public function delete(Model $model): Model;
}
