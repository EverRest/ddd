<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\IEventRepository;
use App\Domain\Shared\ITask;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class DeleteEvent implements ITask
{
    /**
     * @param IEventRepository $eventRepository
     */
    public function __construct(
        private readonly IEventRepository $eventRepository,
    ) {
    }

    /**
     * @param Model $model
     * @return Model
     *
     * @throws Throwable
     */
    public function run(Model $model): Model
    {
        return $this->eventRepository->destroy($model);
    }
}
