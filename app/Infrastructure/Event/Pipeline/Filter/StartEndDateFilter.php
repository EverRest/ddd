<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Pipeline\Filter;

use App\Domain\Event\IEventRepository;
use Closure;
use Illuminate\Support\Facades\App;

class StartEndDateFilter
{
    /**
     * @param mixed $query
     * @param Closure $next
     *
     * @return mixed
     */
    public function __invoke(mixed $query, Closure $next): mixed
    {
        return $this->when(
            $query,
            function ($query) {
                $request = request();
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                /** @var IEventRepository $repository */
                $repository = App::make(IEventRepository::class);
                $repository->filterEventsWithFromToQuery($query, $startDate, $endDate);
                return $query;
            }
        );
    }

    /**
     * @param mixed $query
     * @param Closure $callback
     *
     * @return mixed
     */
    public function when(mixed $query, Closure $callback): mixed
    {
        $request = request();
        return $request->has('end')&&$request->has('start') ? $callback($query) : $query;
    }
}
