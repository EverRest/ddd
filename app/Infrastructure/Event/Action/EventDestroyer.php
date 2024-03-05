<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Action;

use App\Domain\Event\IEventRepository;
use App\Domain\Shared\IAction;
use App\Infrastructure\Event\Task\DeleteEvent;
use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class EventDestroyer implements IAction
{
    /**
     * @param IEventRepository $eventRepository
     */
    public function __construct(private readonly IEventRepository $eventRepository,)
    {
    }

    /**
     * @param EventModel $eventModel
     *
     * @return EventModel
     * @throws Throwable
     */
    public function run(Model $eventModel): Model
    {
        (new DeleteEvent($this->eventRepository))->run($eventModel);

        return $eventModel;
    }
}
