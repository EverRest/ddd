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
     * @param string $end
     */
    public function __construct(
        protected readonly EventModel $event,
        protected string $end= '',
    )
    {
        if(!$this->end) {
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
        $startDate = Carbon::parse($value);
        $endDate = Carbon::parse($this->end);

        return $endDate->diffInHours($endDate) <= 24;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The event can not be longer then 24 hours. You can create a recurring event instead.';
    }
}
