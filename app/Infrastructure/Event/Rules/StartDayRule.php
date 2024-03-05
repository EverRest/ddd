<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

final class StartDayRule implements Rule
{
    /**
     * @param EventModel $event
     * @param string|null|int $end
     */
    public function __construct(
        protected readonly EventModel $event,
        protected string|null|int $end,
    )
    {
        if (!is_string($this->end) && !is_null($this->end)) {
            throw new \InvalidArgumentException('Invalid type for $end property. Should be string or null.');
        }

        if (!$this->end) {
            $this->end = $this->event->end;
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
        $startDate = $value ? Carbon::parse($value) :
            Carbon::createFromTimestamp($this->event->start);
        $endDate = Carbon::parse($this->end);

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
