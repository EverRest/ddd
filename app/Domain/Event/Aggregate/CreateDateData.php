<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Domain\Event\Aggregate\Cast\CarbonDate;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

class CreateDateData extends Data
{
    /**
     * @var int $duration
     */
    public int $duration;

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param array $dates
     */
    public function __construct(
        #[MapInputName('start')]
        public readonly Carbon $start,
        #[MapInputName('end')]
        public readonly Carbon $end,
        #[MapInputName('dates')]
        public readonly array  $dates,
    )
    {
        $this->duration = $this->end->clone()->diffInSeconds($this->start);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            function (Carbon $date) {
                return [
                    'start' => $date,
                    'end' => $date->clone()->addSeconds($this->duration),
                ];
            },
            $this->dates
        );
    }
}
