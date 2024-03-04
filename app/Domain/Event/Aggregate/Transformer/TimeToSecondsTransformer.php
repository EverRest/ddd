<?php
declare(strict_types=1);

namespace App\Domain\Event\Aggregate\Transformer;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

final class TimeToSecondsTransformer implements Transformer
{

    /**
     * @param DataProperty $property
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform(DataProperty $property, mixed $value): mixed
    {
        if ($value) {
            return Carbon::parse($value)->diffInSeconds(Carbon::today());
        }
        return $value;
    }
}
