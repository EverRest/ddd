<?php

namespace App\Domain\Event;

use App\Domain\Shared\ICrudService;
use Illuminate\Support\Collection;

interface IEventService
{
    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return Collection
     */
    public function filterEventsWithFromTo(string $startDate, string $endDate): Collection;

    /**
     * @param string $startDate
     * @param string $endDate
     * @param string|null $repeatUntil
     *
     * @return bool
     */
    public function checkOverlapping(string $startDate, string $endDate, ?string $repeatUntil): bool;
}
