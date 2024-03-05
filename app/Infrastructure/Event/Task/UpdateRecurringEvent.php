<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\UpdateEventData;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Shared\ITask;
use App\Infrastructure\Event\Trait\HasRemoveEmptyValuesFromArray;
use App\Infrastructure\Laravel\Model\EventModel;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateRecurringEvent implements ITask
{
    use HasRemoveEmptyValuesFromArray;

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var IEventRepository $eventRepository
     */
    private IEventRepository $eventRepository;

    /**
     * @var IRecurringPatternRepository $recurringPatternRepository
     */
    private IRecurringPatternRepository $recurringPatternRepository;

    public function __construct()
    {
        $this->eventRepository = App::make(IEventRepository::class);
        $this->recurringPatternRepository = App::make(IRecurringPatternRepository::class);
    }

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Throwable
     */
    public function run(Model $model, array $attributes): Model
    {
        DB::beginTransaction();
        try {
            $hasRecurringPatternChanges = !empty(Arr::except($attributes, ['title', 'description']));
            if (!$hasRecurringPatternChanges) {
                $this->updateWithoutRecurringPatternChanges($model, $attributes);
            } else {
                $parent = $model->parent ?? $model;
                $oldRecurringPattern = $parent->recurringPattern;
                $data = $this->prepareRecurringEventData($parent, $attributes);
                $recurringPattern = ( new CreateRecurrentPattern())
                    ->run($data);
                $model = $this->updateEventWithNewRecurringPattern(
                    $model,
                    $data,
                    $recurringPattern,
                    $parent,
                    $oldRecurringPattern
                );
            }
            DB::commit();

            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return Model
     * @throws Throwable
     */
    private function updateWithoutRecurringPatternChanges(Model $model, array $attributes): Model
    {
        $updateEventData = UpdateEventData::from($attributes);
        $data = $this->removeEmptyValues($updateEventData->toArray());
        $this->eventRepository->update($model, $data);

        return $model;
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @param Model $recurringPattern
     * @param Model $parent
     * @param Model $oldRecurringPattern
     *
     * @return EventModel
     * @throws Throwable
     */
    private function updateEventWithNewRecurringPattern(
        Model $model,
        array $attributes,
        Model $recurringPattern,
        Model $parent,
        Model $oldRecurringPattern
    ): Model {
        /** @var EventModel $model */
        $model = $this->eventRepository
            ->update(
                $model,
                [
                    ...Arr::except($attributes, ['frequency', 'repeat_until',]),
                    'parent_id' => null,
                    'recurring_pattern_id' => $recurringPattern->id,
                    ]
            );
        $this->recurringPatternRepository->destroy($oldRecurringPattern);
        if ($parent->id !== $model->id) {
            $this->eventRepository->destroy($parent);
        }
        (new StoreRecurringEvent($this->eventRepository))
            ->run($model, $recurringPattern, $attributes,);

        return $model;
    }

    /**
     * @param EventModel $parent
     * @param array $attributes
     *
     * @return array
     */
    private function prepareRecurringEventData(EventModel $parent, array $attributes): array
    {
        $start = Arr::get(
            $attributes,
            'start',
            Carbon::createFromTimestamp($parent->start)
                ->format(self::DATE_FORMAT)
        );
        $end = Arr::get(
            $attributes,
            'end',
            Carbon::createFromTimestamp($parent->end)
                ->format(self::DATE_FORMAT)
        );
        $frequency = Arr::get(
            $attributes,
            'frequency',
            $parent->recurringPattern
                ->recurringType
                ->recurring_type
        );
        $repeatUntil = Arr::get(
            $attributes,
            'repeat_until',
            Carbon::createFromTimestamp($parent->recurringPattern->repeat_until)
                ->format(self::DATE_FORMAT)
        );
        return [
            'title' => Arr::get($attributes, 'title', $parent->title),
            'description' => Arr::get($attributes, 'description', $parent->description),
            'start' => $start,
            'end' => $end,
            'frequency' => $frequency,
            'repeat_until' => $repeatUntil,
        ];
    }
}
