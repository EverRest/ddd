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
final class DateDto extends Data
{
    /**
     * @param Carbon|string $start
     * @param Carbon|string $datePattern
     * @param Carbon|string $end
     * @param int|string|null $start_time
     * @param int|string|null $end_time
     * @param Carbon|string|null $repeat_until
     */
    public function __construct(
        #[MapInputName('start')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon|string   $start,
        #[MapInputName('start')]
        #[WithTransformer(DateToPatternTransformer::class)]
        public readonly array|string    $date_pattern,
        #[MapInputName('end')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon|string   $end,
        #[MapInputName('start')]
        #[WithTransformer(TimeToSecondsTransformer::class)]
        public readonly int|null|string $start_time,
        #[MapInputName('end')]
        #[WithTransformer(TimeToSecondsTransformer::class)]
        public readonly int|null|string $end_time,
        #[MapInputName('repeat_until')]
        #[WithCastable(CarbonDate::class)]
        public readonly Carbon|string|null   $repeat_until,
    )
    {
    }
}
