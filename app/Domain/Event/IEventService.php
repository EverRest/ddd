<?php

namespace App\Domain\Event;

use App\Infrastructure\Laravel\Model\EventModel;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

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

    /**
     * @param EventModel $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Exception|Throwable
     */
    public function update(EventModel $model, array $attributes): EventModel;
}
