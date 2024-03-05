<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use App\Domain\Event\IEventService;
use App\Infrastructure\Event\Action\OverlappingChecker;
use App\Infrastructure\Event\Task\CheckIsEventOverlapping;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;

final class NoOverlappingRecurringDates implements Rule
{
    /**
     * @param int|null $eventId
     * @param string $end
     * @param string|null $repeat_until
     */
    public function __construct(
        protected readonly ?int $eventId = null,
        protected readonly string $end = '',
        protected readonly ?string $repeat_until = null,
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
        $checker = App::make(CheckIsEventOverlapping::class);

        return $checker->run($value, $this->end, $this->repeat_until);
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The recurring dates overlap with existing events or patterns.';
    }
}
