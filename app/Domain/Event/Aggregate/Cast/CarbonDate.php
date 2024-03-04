<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate\Cast;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;
use Illuminate\Support\Carbon;

final class CarbonDate implements Castable
{
    public function __construct(public string $date) {

    }

    /**
     * @param ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new class implements Cast {
            public function cast(DataProperty $property, mixed $value, array $context): Carbon {
                return Carbon::parse($value, 'CET')->setTimezone('UTC');
            }
        };
    }
}
