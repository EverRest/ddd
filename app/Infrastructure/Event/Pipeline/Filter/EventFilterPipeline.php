<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Pipeline\Filter;

use Illuminate\Database\Eloquent\Builder;
use League\Pipeline\Pipeline;

class EventFilterPipeline
{
    /**
     * @var array $filters
     */
    protected array $filters = [
        StartEndDateFilter::class,
    ];

    /**
     * EventFilterPipeline constructor.
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        $pipeline = new Pipeline();
        foreach ($this->filters as $filter => $value) {
            $filterInstance = new $filter($value);
            $pipeline = $pipeline->pipe(
                fn($payload) => $filterInstance($payload[0], $payload[1])
            );
        }

        return $pipeline->process([
            $query,
            fn($query) => $query
        ]);
    }
}
