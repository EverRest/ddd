<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Filter;

use App\Domain\Event\IEventRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class StartEndDateFilter
{
    /**
     * @var array $filter_keys
     */
    private array $filter_keys = [
        'start',
        'end',
    ];

    /**
     * @param mixed $query
     * @param array $filter
     *
     * @return mixed
     */
    public function filter(mixed $query, array $filter): mixed
    {
        return $query->when(
            Arr::has($filter, $this->filter_keys),
            function ($query) use ($filter) {
                /** @var IEventRepository $repository */
                $repository = App::make(IEventRepository::class);
                $repository->filterEventsWithFromToQuery($query, $filter['start'], $filter['end']);

                return $query;
            }
        );
    }
}
