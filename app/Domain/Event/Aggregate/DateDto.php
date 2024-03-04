<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use App\Domain\Event\Aggregate\Transformer\DateToPatternTransformer;
use App\Domain\Event\Aggregate\Transformer\TimeToSecondsTransformer;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

/**
 * @property-read Carbon $start
 * @property-read array|string $datePattern
 * @property-read Carbon $end
 * @property-read Carbon $repeat_until
 * @property-read int|null $start_time
 * @property-read int|null $end_time
 */
final class DateDto extends Data
{
    /**
     * @param Carbon|string $start
     * @param array|string $date_pattern
     * @param Carbon|string $end
     * @param int|null|string $start_time
     * @param int|null|string $end_time
     * @param Carbon|string|null $repeat_until
     */
    public function __construct(
        #[MapInputName('start')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon|string $start,
        #[MapInputName('date_pattern')]
        public readonly array|string $date_pattern,
        #[MapInputName('end')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon|string $end,
        #[MapInputName('start_time')]
        #[WithTransformer(TimeToSecondsTransformer::class)]
        public readonly int|null|string $start_time,
        #[MapInputName('end_time')]
        #[WithTransformer(TimeToSecondsTransformer::class)]
        public readonly int|null|string $end_time,
        #[MapInputName('repeat_until')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon|string|null $repeat_until,
    ) {
    }
}
