<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Request;

use App\Infrastructure\Event\Rules\EndDayRule;
use App\Infrastructure\Event\Rules\NoOverlappingRecurringDates;
use App\Infrastructure\Event\Rules\StartDayRule;
use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $start
 * @property string $end
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
        $event = request()
            ->route('event');

        return [
            'title' => ['sometimes', 'min:3', 'max:255', 'string'],
            'description' => ['sometimes', 'min:3', 'max:255', 'string'],
            'start' => [
                'sometimes',
                'date',
                new NoOverlappingRecurringDates($event->id),
                new StartDayRule($event, $this->end),
            ],
            'end' => [
                'sometimes',
                'date',
                'after:start',
                new EndDayRule($event, $this->start),
            ],
            'repeat_until' => ['sometimes', 'date', 'after:end'],
            'frequency' => ['sometimes', 'string', 'in:daily,weekly,monthly,yearly'],
        ];
    }
}
