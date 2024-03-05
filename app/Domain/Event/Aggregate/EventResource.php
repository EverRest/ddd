<?php

declare(strict_types=1);

namespace App\Domain\Event\Aggregate;

use App\Infrastructure\Laravel\Model\EventModel;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @mixin EventModel
 *
 * @property int $id
 * @property string $title
 *  @property string $description
 * @property int $start
 * @property int $end
 * @property RecurringPatternModel $recurringPattern
 */
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(mixed $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start' => Carbon::createFromTimestamp($this->start)->format('Y-m-d H:i:s'),
            'end' => Carbon::createFromTimestamp($this->end)->format('Y-m-d H:i:s'),
            'repeat_until' => $this->recurringPattern?->repeat_until ?
                Carbon::createFromTimestamp($this->recurringPattern->repeat_until)->format('Y-m-d H:i:s') : null,
            'frequency' => $this->recurringPattern->recurringType?->recurring_type,
        ];
    }
}
