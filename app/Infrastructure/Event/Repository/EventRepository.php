<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Repository;

use App\Domain\Event\IEventRepository;
use App\Domain\Shared\Repository;
use App\Infrastructure\Event\Filter\StartEndDateFilter;
use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

final class EventRepository extends Repository implements IEventRepository
{
    /**
     * @var string $model
     */
    protected string $model = EventModel::class;

    /**
     * @var array $with
     */
    protected array $with = ['recurringPattern.recurringType',];

    /**
     * @var string $default_order
     */
    protected string $default_order = 'DESC';

    /**
     * @var string $default_sort
     */
    protected string $default_sort = 'start';

    /**
     * @param array $data
     *
     * @return bool
     */
    public function checkOverlapping(array $data): bool
    {
        $start = date(Arr::get($data, 'start'));
        $end = date(Arr::get($data, 'end'));

        return !$this->query()
            ->whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end])
            ->whereHas('recurringPattern', function ($query) use ($start) {
                $query->where(
                    function ($query) use ($start) {
                        $query->whereNull('repeat_until')
                            ->orWhere('repeat_until', '>=', $start);
                    }
                );
            })->exists();
    }

    /**
     * @param mixed $query
     * @param string $start
     * @param string $end
     *
     * @return mixed
     */
    public function filterEventsWithFromToQuery(mixed $query, string $start, string $end): mixed
    {
        $from = Carbon::parse($start);
        $to = Carbon::parse($end);
        return $query->whereBetween('start', [$from, $to])
            ->orWhereBetween('end', [$from, $to]);
    }
}
