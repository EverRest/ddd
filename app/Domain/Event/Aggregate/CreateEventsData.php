<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use App\Infrastructure\Event\Service\DateHelper;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use App\Infrastructure\Laravel\Model\RecurringTypeModel;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

final class CreateEventsData extends Data
{
    /**
     * @param string $title
     * @param string $description
     * @param RecurringPatternModel $recurring_pattern
     * @param RecurringTypeModel $recurring_type
     * @param string $start
     * @param Carbon $end
     * @param int $parent_id
     */
    public function __construct(
        #[MapInputName('title')]
        public readonly string $title,
        #[MapInputName('description')]
        public readonly string $description,
        #[MapInputName('recurring_pattern')]
        public readonly RecurringPatternModel $recurring_pattern,
        #[MapInputName('recurring_type')]
        public RecurringTypeModel $recurring_type,
        #[MapInputName('start')]
        #[WithCastable(CarbonDate::class)]
        public readonly string $start,
        #[MapInputName('end')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon $end,
        #[MapInputName('parent_id')]
        public readonly int $parent_id
    ) {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return CreateEventData::collection(
            $this->mapDatesByPattern()
        )->toArray();
    }

    /**
     * @return array
     */
    private function mapDatesByPattern(): array
    {
        $repeatUntil = Carbon::createFromTimestamp($this->recurring_pattern->repeat_until);
        $duration = $this->end->clone()->diffInSeconds($this->start);

        return array_map(
            function ($date) use ($duration) {
                return [
                    'start' => $date,
                    'end' => $date->clone()->addSeconds($duration),
                    'recurring_pattern_id' => $this->recurring_pattern->id,
                    'title' => $this->title,
                    'description' => $this->description,
                    'parent_id' => $this->parent_id,
                ];
            },
            DateHelper::mapDatesByPattern($this->recurring_type->recurring_type, $this->start, $repeatUntil)
        );
    }
}
