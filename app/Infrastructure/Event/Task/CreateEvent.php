<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\CreateEventsData;
use App\Domain\Event\IEventRepository;
use App\Domain\Shared\ITask;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateEvent implements ITask
{
    /**
     * @var IEventRepository $eventRepository
     */
    private IEventRepository $eventRepository;

    /**
     * CreateEvent constructor.
     */
    public function __construct()
    {
        $this->eventRepository = App::make(IEventRepository::class);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     * @throws Exception|Throwable
     */
    public function run(array $attributes): Model
    {
        DB::beginTransaction();
        try {
            $recurringPattern = (new CreateRecurrentPattern())->run($attributes);
            $event = $this->eventRepository
                ->firstOrCreate([
                    ...Arr::except($attributes, ['repeat_until', 'frequency',]),
                    'parent_id' => null,
                    'recurring_pattern_id' => $recurringPattern->id,
                ]);
            if ($recurringPattern->repeat_until) {
                $this->saveRecurrenceEvents($event, $recurringPattern, $attributes);
            }
            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Model $event
     * @param Model $recurringPattern
     * @param array $attributes
     */
    private function saveRecurrenceEvents(
        Model $event,
        Model $recurringPattern,
        array $attributes
    ): void {
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
