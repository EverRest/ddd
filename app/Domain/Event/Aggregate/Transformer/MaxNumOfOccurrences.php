<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate\Transformer;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

final class MaxNumOfOccurrences implements Transformer
{
    /**
     * @param DataProperty $property
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform(DataProperty $property, mixed $value): mixed
    {
        return strtoupper($value);
    }
}
