<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Pipeline\Filter;

use App\Domain\Event\IEventRepository;
use App\Domain\Event\IEventService;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;

class StartEndDateFilter
{
    /**
     * StartEndDateFilter constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Builder $query
     * @param Closure $next
     *
     * @return Builder
     */
    public function __invoke(Builder $query, Closure $next): Builder
    {
        $request = request();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            /** @var IEventRepository $repository */
            $repository = App::make(IEventRepository::class);
            $repository->filterEventsWithFromToQuery($query, $startDate, $endDate);
        }

        return $next($query);
    }
}
