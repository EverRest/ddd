<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Action;

use App\Domain\Shared\IAction;
use App\Infrastructure\Event\Task\UpdateOrdinaryEvent;
use App\Infrastructure\Event\Task\UpdateRecurringEvent;
use App\Infrastructure\Event\Trait\HasRemoveEmptyValuesFromArray;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class   EventUpdater implements IAction
{
    use HasRemoveEmptyValuesFromArray;

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return Model
     * @throws Throwable
     */
    public function run(Model $model, array $attributes): Model
    {
        if ($model->recurringPattern->repeat_until) {
            $task = new UpdateRecurringEvent();
        } else {
            $task = new UpdateOrdinaryEvent();
        }

        return $task->run($model, $attributes);
    }
}
