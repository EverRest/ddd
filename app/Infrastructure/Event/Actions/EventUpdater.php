<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Actions;

use App\Domain\Event\Aggregate\CreateEventsData;
use App\Domain\Event\Aggregate\CreateRecurringPatternData;
use App\Domain\Event\Aggregate\UpdateEventData;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IRecurringPatternService;
use App\Infrastructure\Event\Trait\HasRemoveEmptyValuesFromArray;
use App\Infrastructure\Laravel\Model\EventModel;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class EventUpdater
{
    use HasRemoveEmptyValuesFromArray;

    public function __construct(
        private readonly IEventRepository         $eventRepository,
        private readonly IRecurringPatternService $recurringPatternService,
    )
    {
    }

    /**
     * @param EventModel $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Throwable
     */
    public function run(EventModel $model, array $attributes): EventModel
    {
        if ($model->recurringPattern->repeat_until) {
            /** @var EventModel $model */
            $model = $this->updateRecurringEvent($model, $attributes);
        } else {
            /** @var EventModel $model */
            $model = $this->updateOrdinaryEvent($model, $attributes);
        }

        return $model->refresh();
    }

    /**
     * @param EventModel $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Throwable
     */
    private function updateRecurringEvent(EventModel $model, array $attributes): EventModel
    {
        DB::beginTransaction();
        try {
            $hasRecurringPatternChanges = !empty(Arr::except($attributes, ['title', 'description']));
            if (!$hasRecurringPatternChanges) {
                $this->updateWithoutRecurringPatternChanges($model, $attributes);
            } else {
                $parent = $model->parent ?? $model;
                $oldRecurringPattern = $parent->recurringPattern;
                $data = [
                    'title' => Arr::get($attributes, 'title', $parent->title),
                    'description' => Arr::get($attributes, 'description', $parent->description),
                    'start' => Arr::get($attributes, 'start', Carbon::createFromTimestamp($parent->start)->format('Y-m-d H:i:s')),
                    'end' => Arr::get($attributes, 'end', Carbon::createFromTimestamp($parent->end)->format('Y-m-d H:i:s')),
                    'frequency' => Arr::get($attributes, 'frequency', $parent->recurringPattern->recurringType->recurring_type),
                    'repeat_until' => Arr::get($attributes, 'repeat_until', Carbon::createFromTimestamp($parent->recurringPattern->repeat_until)->format('Y-m-d H:i:s')),
                ];
                $recurringPatternDto = CreateRecurringPatternData::from(Arr::except($data, ['title', 'description',]));
                /** @var RecurringPatternModel $recurringPattern */
                $recurringPattern = $this->recurringPatternService->create($recurringPatternDto->toArray());
                /** @var EventModel $model */
                $model = $this->eventRepository->update($model, [...Arr::except($data, ['frequency', 'repeat_until',]), 'parent_id' => null, 'recurring_pattern_id' => $recurringPattern->id,]);
                $this->recurringPatternService->delete($oldRecurringPattern);
                if ($parent->id !== $model->id) {
                    $this->eventRepository->destroy($parent);
                }
                $this->saveRecurrenceEvents($model, $recurringPattern, $attributes);
            }

            DB::commit();

            return $model;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param EventModel $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Throwable
     */
    private function updateOrdinaryEvent(EventModel $model, array $attributes): EventModel
    {
        $updateEventData = UpdateEventData::from($attributes);
        $data = $this->removeEmptyValues($updateEventData->toArray());
        $this->eventRepository->update($model, $data);

        return $model->refresh();
    }

    /**
     * @param EventModel $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Throwable
     */
    private function updateWithoutRecurringPatternChanges(EventModel $model, array $attributes): EventModel
    {
        $updateEventData = UpdateEventData::from($attributes);
        $data = $this->removeEmptyValues($updateEventData->toArray());
        $this->eventRepository->update($model, $data);

        return $model;
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
            $this->eventRepository->store($event);
        }
    }
}
