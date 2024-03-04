<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use App\Domain\Event\IRecurringTypeService;
use App\Infrastructure\Event\Service\Domain\RecurringTypeService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

final class CreateRecurringPatternData extends Data
{
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param Carbon|null $repeat_until
     * @param string|null $frequency
     */
    public function __construct(
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon $start,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon $end,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon|null $repeat_until,
        #[MapInputName('frequency')]
        public readonly string|null $frequency
    ) {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'repeat_until'      => $this->repeat_until,
            'day_of_week'       => $this->start->clone()->dayOfWeek,
            'day_of_month'      => $this->start->clone()->day,
            'week_of_month'     => $this->start->clone()->weekOfMonth,
            'month_of_year'     => $this->start->clone()->month,
            'recurring_type_id' => $this->getRecurringTypeId(),
        ];
    }

    /**
     * @return int|null
     */
    private function getRecurringTypeId(): ?int
    {
        if ($this->frequency) {
            $recurringTypeService = App::make(IRecurringTypeService::class);
            $recurringType = $recurringTypeService->getRecurringTypeByCode($this->frequency);
            return $recurringType->id;
        }

        return null;
    }
}
