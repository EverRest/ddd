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
 * @property-read Carbon $datePattern
 * @property-read Carbon $end
 * @property-read Carbon $repeat_until
 * @property-read int|null $start_time
 * @property-read int|null $end_time
 */
final class PatternDto extends Data
{
    /**
     * @param Carbon $start
     * @param Carbon $datePattern
     * @param Carbon $end
     * @param int|null $start_time
     * @param int|null $end_time
     * @param Carbon $repeat_until
     */
    public function __construct(
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon   $start,
        #[WithTransformer(DateToPatternTransformer::class)]
        #[MapInputName('start')]
        public readonly string|array   $datePattern,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon   $end,
        #[WithTransformer(TimeToSecondsTransformer::class)]
        #[MapInputName('start')]
        public readonly int|null $start_time,
        #[WithTransformer(TimeToSecondsTransformer::class)]
        #[MapInputName('end')]
        public readonly int|null $end_time,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon   $repeat_until,
    )
    {
    }
}
