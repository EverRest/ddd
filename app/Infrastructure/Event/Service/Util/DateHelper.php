<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Service\Util;

use App\Domain\Event\Enum\RecurringTypeEnum;
use Illuminate\Support\Carbon;

class DateHelper
{
    /**
     * @return int
     */
    public static function getMaxNumOfOccurrences(string $type, string|Carbon $start, string|Carbon $end): int
    {
        $startDate = is_string($start) ? Carbon::parse($start) : $start;
        $endDate = is_string($end) ? Carbon::parse($start) : $end;
        return match ($type) {
            RecurringTypeEnum::DAILY->value => $endDate->clone()->diffInDays($startDate),
            RecurringTypeEnum::WEEKLY->value => $endDate->clone()->diffInWeeks($startDate),
            RecurringTypeEnum::MONTHLY->value => $endDate->clone()->diffInMonths($startDate),
            RecurringTypeEnum::YEARLY->value => $endDate->clone()->diffInYears($startDate),
        };
    }

    /**
     * @param string $type
     * @param string|Carbon $start
     * @param string|Carbon $repeat_until
     *
     * @return array
     */
    public static function mapDatesByPattern(string $type, string|Carbon $start, string|Carbon $repeat_until): array
    {
        $dates = [];
        $startDate = is_string($start) ? Carbon::parse($start) : $start;
        $occurrences = self::getMaxNumOfOccurrences($type, $startDate, $repeat_until);
        for ($i = 1; $i <= $occurrences; $i++) {
            $dates[] = match ($type) {
                RecurringTypeEnum::DAILY->value => $startDate->clone()->addDays($i),
                RecurringTypeEnum::WEEKLY->value => $startDate->clone()->addWeeks($i),
                RecurringTypeEnum::MONTHLY->value => $startDate->clone()->addMonths($i),
                RecurringTypeEnum::YEARLY->value => $startDate->clone()->addYears($i),
            };
        }

        return $dates;
    }
}
