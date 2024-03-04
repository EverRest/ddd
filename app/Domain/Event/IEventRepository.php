<?php

namespace App\Domain\Event;

use App\Domain\Shared\IRepository;
use Illuminate\Database\Query\Builder;

interface IEventRepository extends IRepository
{
    /**
     * @param array $data
     *
     * @return bool
     */
    public function checkOverlapping(array $data): bool;

    /**
     * @param Builder $query
     * @param string $start
     * @param string $end
     *
     * @return Builder
     */
    public function filterEventsWithFromToQuery(Builder $query, string $start, string $end): Builder;
}
