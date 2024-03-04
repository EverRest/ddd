<?php
declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

final class CreateEventData extends Data
{
    /**
     * @param string $recurring_pattern_id
     * @param int|null $parent_id
     * @param string $title
     * @param string $description
     * @param string $start
     * @param string $end
     */
    public function __construct(
        #[MapInputName('recurring_pattern_id')]
        public readonly string $recurring_pattern_id,
        #[MapInputName('parent_id')]
        public int|null        $parent_id,
        #[MapInputName('title')]
        public readonly string $title,
        #[MapInputName('description')]
        public readonly string $description,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly string $start,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly string $end,
    )
    {
    }
}
