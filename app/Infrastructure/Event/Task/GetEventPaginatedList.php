<?php

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\IEventRepository;
use App\Domain\Shared\ITask;
use Illuminate\Contracts\Pagination\Paginator;

class GetEventPaginatedList implements ITask
{
    /**
     * @param IEventRepository $eventRepository
     */
    public function __construct(
        private readonly IEventRepository $eventRepository,
    ) {
    }

    /**
     * @param array $attributes
     *
     * @return Paginator
     */
    public function run(array $attributes): Paginator
    {
        return $this->eventRepository->getPaginatedList($attributes);
    }
}
