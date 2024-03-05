<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Pipeline\Filter;

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
     * @param mixed $query
     *
     * @return mixed
     */
    public function apply(mixed $query): mixed
    {
        $pipeline = new Pipeline();
        foreach ($this->filters as $filterClass => $value) {
            $pipeline = $pipeline->pipe(
                fn($payload) => new $filterClass()
            );
        }

        return $pipeline->process([
            $query,
            fn($query) => $query
        ]);
    }
}
