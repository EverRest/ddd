<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use App\Infrastructure\Event\Trait\HasRemoveEmptyValuesFromArray;
use App\Infrastructure\Laravel\Model\RecurringTypeModel as RecurringType;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

final class UpdateRecurringPatternData extends Data
{
    use HasRemoveEmptyValuesFromArray;

    /**
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param Carbon|null $repeat_until
     * @param RecurringType $recurring_type_model
     */
    public function __construct(
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon|null $start,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon|null $end,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon|null $repeat_until,
        #[MapInputName('recurring_type')]
        public readonly RecurringType|null $recurring_type_model
    ) {
    }

    /**
     * @return array
     */
    public function toRecurringPatternAttributes(): array
    {
        $dtoAttributes = [
            'recurring_type_id' => $this->recurring_type_model->id,
            'start' => $this->start->clone()->diffInSeconds($this->start->clone()->startOfDay()),
            'end' => $this->end->clone()->diffInSeconds($this->end->clone()->startOfDay()),
            'repeat_until' => $this->repeat_until,
            'day_of_week' => $this->start->clone()->dayOfWeek,
            'week_of_month' => $this->start->clone()->weekOfMonth,
            'month_of_year' => $this->start->clone()->month,
        ];

        return $this->removeEmptyValues($dtoAttributes);
    }
}
