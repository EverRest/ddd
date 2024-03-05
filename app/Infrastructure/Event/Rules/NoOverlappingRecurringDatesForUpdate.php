<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use App\Infrastructure\Event\Task\CheckIsEventOverlappingForUpdate;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

final class NoOverlappingRecurringDatesForUpdate implements Rule
{
    /**
     * @param Model $event
     * @param string|null $start
     * @param string|null $end
     * @param string|null $repeat_until
     * @param string|null $frequency
     */
    public function __construct(
        protected readonly Model $event,
        protected readonly ?string $start,
        protected readonly ?string $end,
        protected readonly ?string $repeat_until = null,
        protected readonly ?string $frequency = null
    ) {
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        /** @var CheckIsEventOverlappingForUpdate $checker */
        $checker = App::make(CheckIsEventOverlappingForUpdate::class);
        return $checker->run(
            $this->event,
            $this->start,
            $this->end,
            $this->repeat_until,
            $this->frequency
        );
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The recurring dates overlap with existing events or patterns.';
    }
}
