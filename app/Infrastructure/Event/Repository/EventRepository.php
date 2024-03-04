<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Repository;

use App\Domain\Event\IEventRepository;
use App\Domain\Shared\Repository;
use App\Infrastructure\Event\Pipeline\Filter\EventFilterPipeline;
use App\Infrastructure\Event\Pipeline\Filter\StartEndDateFilter;
use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

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
            ->whereHas('recurringPattern', function (Builder $query) use ($start) {
                $query->where(
                    function (Builder $query) use ($start) {
                        $query->whereNull('repeat_until')
                            ->orWhere('repeat_until', '>=', $start);
                    }
                );
            })->exists();
    }

    /**
     * @param Builder $query
     * @param string $start
     * @param string $end
     *
     * @return Builder
     */
    public function filterEventsWithFromToQuery(Builder $query, string $start, string $end): Builder
    {
        $from = date($start);
        $to = date($end);
        return $query->whereBetween('start', [$from, $to])
            ->orWhereBetween('end', [$from, $to]);
    }

    /**
     * @param $query
     * @param array $filter
     *
     * @return Builder
     */
    protected function filter($query, array $filter): Builder
    {
        $filters = [
            StartEndDateFilter::class => [
                'start' => Arr::get($filter, 'start'),
                'end' => Arr::get($filter, 'end'),
            ],
        ];
        $pipeline = new EventFilterPipeline($filters);
        $query = $pipeline->apply($query);

        return parent::filter($query, Arr::except($filter, ['start', 'end']));
    }
}
