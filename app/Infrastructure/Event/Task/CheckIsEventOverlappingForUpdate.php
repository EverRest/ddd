<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\CreateRecurringPatternData;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Shared\ITask;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class CheckIsEventOverlappingForUpdate implements ITask
{
    /**
     * @param Model $event
     * @param string|null $start
     * @param string|null $end
     * @param string|null $repeat_until
     * @param string|null $frequency
     * @return bool
     * @throws Exception
     */
    public function run(
        Model $event,
        ?string $start = null,
        ?string $end = null,
        ?string $repeat_until,
        ?string $frequency
    ): bool {
        $start = $start ?? Carbon::createFromTimestamp($event->start)
            ->format('Y-m-d H:i:s');
        $end = $end ?? Carbon::createFromTimestamp($event->end)
            ->format('Y-m-d H:i:s');
        $repeatUntil = $repeat_until ?? Carbon::createFromTimestamp($event->RecurringPattern->repeat_until)
            ->format('Y-m-d H:i:s');
        $frequency = $frequency ?? $event->RecurringPattern->recurringType?->recurring_type;
        /** @var IEventRepository $eventRepository */
        $eventRepository = App::make(IEventRepository::class);
        if ($repeatUntil && $frequency) {
            /** @var CreateRecurringPatternData $recurringPatternDto */
            $recurringPatternDto = CreateRecurringPatternData::from([
                'start' => $start,
                'end' => $end,
                'repeat_until' => $repeatUntil,
                'frequency' => $frequency,
            ]);
            $recurringPatternRepository = App::make(IRecurringPatternRepository::class);
            $criteria = $recurringPatternDto->toArray();
            $query = $recurringPatternRepository->query()->where('repeat_until', '>=', $start);

            return match ($frequency) {
                'daily' => !$eventRepository->query()
                    ->where('id', '!=', $event->id)
                    ->where(function ($query) use ($start, $end) {
                        $query->where('start', '<', $end)
                            ->where('end', '>', $start);
                    })->exists(),
                'weekly' => !$query->where('id', '!=', $event->recurringPattern->id)
                    ->where(function ($query) use ($criteria) {
                        $query->where('day_of_week', Arr::get($criteria, 'day_of_week'));
                    })->exists(),
                'monthly' => !$query->where('id', '!=', $event->recurringPattern->id)
                    ->where(function ($query) use ($criteria) {
                        $query->where('day_of_week', Arr::get($criteria, 'day_of_week'))
                            ->where('day_of_month', Arr::get($criteria, 'day_of_month'))
                            ->where('week_of_month', Arr::get($criteria, 'week_of_month'));
                    })->exists(),
                'yearly' => !$query->where('id', '!=', $event->recurringPattern->id)
                    ->where(function ($query) use ($criteria) {
                        $query->where('day_of_week', Arr::get($criteria, 'day_of_week'))
                            ->where('day_of_month', Arr::get($criteria, 'day_of_month'))
                            ->where('week_of_month', Arr::get($criteria, 'week_of_month'))
                            ->where('month_of_year', Arr::get($criteria, 'month_of_year'));
                    })->exists(),
                default => throw new Exception('Unsupported frequency'),
            };
        } else {
            return $eventRepository->query()
                ->where('id', '!=', $event->id)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start', '<', $end)
                        ->where('end', '>', $start);
                })->exists();
        }
    }
}
