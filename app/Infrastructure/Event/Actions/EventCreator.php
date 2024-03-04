<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Actions;

use App\Domain\Event\Aggregate\CreateEventsData;
use App\Domain\Event\Aggregate\CreateRecurringPatternData;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IRecurringPatternService;
use App\Infrastructure\Laravel\Model\EventModel;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class EventCreator
{
    /**
     * @param IRecurringPatternService $recurringPatternService
     * @param IEventRepository $eventRepository
     */
    public function __construct(
        private readonly IRecurringPatternService $recurringPatternService,
        private readonly IEventRepository $eventRepository,
    )
    {
    }
    /**
     * @param array $attributes
     *
     * @return EventModel
     * @throws Exception|Throwable
     */
    public function run(array $attributes): EventModel
    {
        DB::beginTransaction();
        try {
            $recurringPatternDto = CreateRecurringPatternData::from([...Arr::except($attributes, ['title', 'description',]),]);
            /** @var RecurringPatternModel $recurringPattern */
            $recurringPattern = $this->recurringPatternService->create($recurringPatternDto->toArray());
            /** @var EventModel $event */
            $event = $this->eventRepository->firstOrCreate([...Arr::except($attributes, ['repeat_until', 'frequency',]), 'parent_id' => null, 'recurring_pattern_id' => $recurringPattern->id,]);
            if ($recurringPattern->repeat_until) {
                $this->saveRecurrenceEvents($event, $recurringPattern, $attributes);
            }
            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param EventModel $event
     * @param RecurringPatternModel $recurringPattern
     * @param array $attributes
     */
    private function saveRecurrenceEvents(EventModel $event, RecurringPatternModel $recurringPattern, array $attributes): void
    {
        $eventsData = [
            ...Arr::only($attributes, ['title', 'description', 'end', 'start',]),
            'recurring_pattern' => $recurringPattern,
            'recurring_type' => $recurringPattern->recurringType,
            'parent_id' => $event->id
        ];
        $createEventsDto = CreateEventsData::from($eventsData);
        $this->saveMany($createEventsDto->toArray());
    }

    /**
     * @param array $data
     */
    private function saveMany(array $data): void
    {
        foreach ($data as $event) {
            $this->eventRepository->firstOrCreate($event);
        }
    }
}
