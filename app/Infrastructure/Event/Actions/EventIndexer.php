<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Actions;

use App\Domain\Event\IEventRepository;
use Illuminate\Contracts\Pagination\Paginator;

class EventIndexer
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
