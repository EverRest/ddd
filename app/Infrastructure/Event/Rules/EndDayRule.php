<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

final class EndDayRule implements Rule
{
    /**
     * @param EventModel $event
     * @param string|null $start
     */
    public function     __construct(
        protected readonly EventModel $event,
        protected   $start,
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
        $endDate = $value ? Carbon::parse($value) :
            Carbon::createFromTimestamp($this->event->end);
        $startDate = Carbon::parse($this->start);

        return $endDate->diffInHours($startDate) <= 24;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The event cannot be longer than 24 hours. You can create a recurring event instead.';
    }
}
