<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

final class EndDayRule implements Rule
{
    /**
     * @param string $start
     */
    public function __construct(
        protected readonly EventModel $event,
        protected string $start = '',
    ) {
        if (!$this->start) {
            $this->start = $this->event->start;
        }
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $endDate = Carbon::parse($value);
        $startDate = Carbon::parse($this->start);

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
