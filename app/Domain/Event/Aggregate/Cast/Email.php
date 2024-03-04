<?php

namespace App\Domain\Event\Aggregate\Cast;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

class Email implements Castable
{
    public function __construct(public string $email) {

    }

    public static function dataCastUsing(...$arguments): Cast
    {
        return new class implements Cast {
            public function cast(DataProperty $property, mixed $value, array $context): mixed {
                return new Email($value);
            }
        };
    }
}
