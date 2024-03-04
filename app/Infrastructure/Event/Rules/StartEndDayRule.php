<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

final class StartEndDayRule implements Rule
{
    /**
     * @param string $end
     */
    public function __construct(
        protected readonly string $end = '',
    )
    {
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $startDate = Carbon::parse($value);
        $endDate = Carbon::parse($this->end);

        return $endDate->diffInHours($startDate) <= 24;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The event can not be longer then 24 hours. You can create a recurring event instead.';
    }
}
