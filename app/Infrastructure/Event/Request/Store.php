<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Request;

use App\Infrastructure\Event\Rules\NoOverlappingRecurringDates;
use App\Infrastructure\Event\Rules\StartEndDayRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Store
 *
 * @property string $title
 * @property string $description
 * @property string $start
 * @property string $end
 * @property string $repeat_until
 * @property string $frequency
 *
 */
final class Store extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array <string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:255', 'string'],
            'description' => ['nullable', 'min:3', 'string'],
            'start' => [
                'required',
                'date:Y-m-d H:i:s',
                new NoOverlappingRecurringDates(null, $this->end, $this->repeat_until),
                new StartEndDayRule($this->end),
            ],
            'end' => ['required', 'date:Y-m-d H:i:s', 'after:start'],
            'repeat_until' => ['required_with:frequency', 'date', 'after:end', 'nullable', 'after:start', 'after:end'],
            'frequency' => ['required_with:repeat_until', 'string', 'in:daily,weekly,monthly,yearly', 'nullable',],
        ];
    }
}
