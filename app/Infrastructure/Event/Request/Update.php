<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Request;

use App\Infrastructure\Event\Rules\EndDayRule;
use App\Infrastructure\Event\Rules\NoOverlappingRecurringDatesForUpdate;
use App\Infrastructure\Event\Rules\StartDayRule;
use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $start
 * @property string $end
 * @property string $repeat_until
 * @property string $frequency
 */
final class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /**
         * @var EventModel $event
         */
        $event = request()->route('event');

        return [
            'title' => ['sometimes', 'min:3', 'max:255', 'string'],
            'description' => ['sometimes', 'min:3', 'max:255', 'string'],
            'start' => [
                'sometimes',
                'date',
                'after:now',
                'before:end',
                new NoOverlappingRecurringDatesForUpdate(
                    $event,
                    $this->start,
                    $this->end,
                    $this->repeat_until,
                    $this->frequency
                ),
                new StartDayRule(
                    $event,
                    $this->end
                ),
            ],
            'end' => [
                'sometimes',
                'date',
                'after:start',
                'after:now',
                new EndDayRule(
                    $event,
                    $this->start
                ),
            ],
            'repeat_until' => ['nullable', 'date', 'after:end'],
            'frequency' => [
                'nullable',
                'string',
                'in:daily,weekly,monthly,yearly'
            ],
        ];
    }
}
