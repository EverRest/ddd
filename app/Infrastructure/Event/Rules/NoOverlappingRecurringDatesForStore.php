<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Rules;

use App\Infrastructure\Event\Task\CheckIsEventOverlappingForStore;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;

final class NoOverlappingRecurringDatesForStore implements Rule
{
    /**
     * @param string $start
     * @param string $end
     * @param string|null $repeat_until
     * @param string|null $frequency
     */
    public function __construct(
        protected readonly string $start,
        protected readonly string $end,
        protected readonly ?string $repeat_until = null,
        protected readonly ?string $frequency = null,
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
        /** @var CheckIsEventOverlappingForStore $checker */
        $checker = App::make(CheckIsEventOverlappingForStore::class);
       return $checker->run($this->start, $this->end, $this->repeat_until, $this->frequency);
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The recurring dates overlap with existing events or patterns.';
    }
}
