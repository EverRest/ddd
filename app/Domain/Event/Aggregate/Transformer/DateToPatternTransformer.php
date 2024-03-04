<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate\Transformer;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

final class DateToPatternTransformer implements Transformer
{
    /**
     * @param DataProperty $property
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform(DataProperty $property, mixed $value): mixed
    {
        if($value) {
            $carbonDate = Carbon::parse($value);

            $value = [
                'day_of_week' => $carbonDate->clone()->dayOfWeek,
                'day_of_month' => $carbonDate->clone()->day,
                'week_of_month' => $carbonDate->clone()->weekOfMonth,
                'month_of_year' => $carbonDate->clone()->month,
            ];
        }

        return $value;
    }
}
