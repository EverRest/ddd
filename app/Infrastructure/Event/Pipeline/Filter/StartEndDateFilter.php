<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Pipeline\Filter;

use App\Domain\Event\IEventService;
use Closure;
use Illuminate\Database\Eloquent\Builder;
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
            /** @var IEventService $service */
            $service = App::make(IEventService::class);
            $service->filterEventsWithFromToQuery($startDate, $endDate);
        }

        return $next($query);
    }
}
