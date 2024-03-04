<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

final class UpdateEventData extends Data
{
    /**
     * @param string|null $title
     * @param string|null $description
     * @param Carbon|null $start
     * @param Carbon|null $end
     */
    public function __construct(
        #[MapInputName('title')]
        public readonly string|null        $title,
        #[MapInputName('description')]
        public readonly string|null        $description,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('start')]
        public  Carbon|null        $start,
        #[WithCastable(CarbonDate::class)]
        #[MapInputName('end')]
        public  Carbon|null        $end,
    )
    {
    }
}
