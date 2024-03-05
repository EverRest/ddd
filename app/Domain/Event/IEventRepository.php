<?php

namespace App\Domain\Event;

use App\Domain\Shared\IRepository;

interface IEventRepository extends IRepository
{
    /**
     * @param array $data
     *
     * @return bool
     */
    public function checkOverlapping(array $data): bool;

    /**
     * @param mixed $query
     * @param string $start
     * @param string $end
     *
     * @return mixed
     */
    public function filterEventsWithFromToQuery(mixed $query, string $start, string $end): mixed;
}
