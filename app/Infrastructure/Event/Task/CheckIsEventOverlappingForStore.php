<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\CreateRecurringPatternData;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Shared\ITask;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class CheckIsEventOverlappingForStore implements ITask
{
    /**
     * @param string $start
     * @param string $end
     * @param string|null $repeatUntil
     * @param string|null $frequency
     *
     * @return bool
     * @throws Exception
     */
    public function run(string $start, string $end, ?string $repeatUntil, ?string $frequency): bool
    {
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
                    ->where(function ($query) use ($start, $end) {
                        $query->where('start', '<', $end)
                            ->where('end', '>', $start);
                    })->exists(),
                'weekly' => !$query->where(function ($query) use ($criteria) {
                    $query->where('day_of_week', Arr::get($criteria, 'day_of_week'));
                })->exists(),
                'monthly' => !$query->where(function ($query) use ($criteria) {
                    $query->where('day_of_week', Arr::get($criteria, 'day_of_week'))
                        ->where('day_of_month', Arr::get($criteria, 'day_of_month'))
                        ->where('week_of_month', Arr::get($criteria, 'week_of_month'));
                })->exists(),
                'yearly' => !$query->where(function ($query) use ($criteria) {
                    $query->where('day_of_week', Arr::get($criteria, 'day_of_week'))
                        ->where('day_of_month', Arr::get($criteria, 'day_of_month'))
                        ->where('week_of_month', Arr::get($criteria, 'week_of_month'))
                        ->where('month_of_year', Arr::get($criteria, 'month_of_year'));
                })->exists(),
                default => throw new Exception('Unsupported frequency'),
            };
        } else {
            return !$eventRepository->query()
                ->where(function ($query) use ($start, $end) {
                    $query->where('start', '<', $end)
                        ->where('end', '>', $start);
                })->exists();
        }
    }
}
