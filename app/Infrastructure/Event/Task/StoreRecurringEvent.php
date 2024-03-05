<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\CreateEventsData;
use App\Domain\Event\IEventRepository;
use App\Domain\Shared\ITask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class StoreRecurringEvent implements ITask
{
    private const EXCEPT_ATTRIBUTES = ['title', 'description', 'end', 'start',];

    /**
     * @param IEventRepository $eventRepository
     */
    public function __construct(
        private readonly IEventRepository $eventRepository,
    ) {
    }

    /**
     * @param Model $event
     * @param Model $recurringPattern
     * @param array $attributes
     *
     * @return void
     */
    public function run(Model $event, Model $recurringPattern, array $attributes): void
    {
        $eventsData = [
            ...Arr::only($attributes, self::EXCEPT_ATTRIBUTES),
            'recurring_pattern' => $recurringPattern,
            'recurring_type' => $recurringPattern->recurringType,
            'parent_id' => $event->id
        ];
        $createEventsDto = CreateEventsData::from($eventsData);
        $this->saveMany($createEventsDto->toArray());
    }

    /**
     * @param array $data
     *
     * @return void
     */
    private function saveMany(array $data): void
    {
        foreach ($data as $event) {
            $this->eventRepository->store($event);
        }
    }
}
