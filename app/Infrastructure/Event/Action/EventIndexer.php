<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Action;

use App\Domain\Event\IEventRepository;
use App\Domain\Shared\IAction;
use App\Infrastructure\Event\Task\GetEventPaginatedList;
use Illuminate\Contracts\Pagination\Paginator;

class EventIndexer implements IAction
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
        return (new GetEventPaginatedList($this->eventRepository))->run($attributes);
    }
}
