<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Trait;

use App\Domain\Event\Enum\RecurringTypeEnum;
use Illuminate\Support\Carbon;

trait HasGetMaxNumOfOccurrences
{
    /**
     * @return int
     */
    protected function getMaxNumOfOccurrences(string $type, string|Carbon $start, string|Carbon $end): int
    {
        $startDate = is_string($start) ? Carbon::parse($start) : $start;
        $endDate = is_string($end) ? Carbon::parse($start) : $end;

        return match ($type) {
            RecurringTypeEnum::DAILY->value => $endDate->diffInDays($startDate),
            RecurringTypeEnum::WEEKLY->value => $endDate->diffInWeeks($startDate),
            RecurringTypeEnum::MONTHLY->value => $endDate->diffInMonths($startDate),
            RecurringTypeEnum::YEARLY->value => $endDate->diffInYears($startDate),
        };
    }
}
