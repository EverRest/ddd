<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Service\Domain;

use App\Domain\Event\Aggregate\DateDto;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IEventService;
use App\Domain\Event\IRecurringPatternService;
use App\Domain\Shared\CrudService;
use App\Infrastructure\Event\Actions\EventCreator;
use App\Infrastructure\Event\Actions\EventDeleter;
use App\Infrastructure\Event\Actions\EventIndexer;
use App\Infrastructure\Event\Actions\EventUpdater;
use App\Infrastructure\Laravel\Model\EventModel;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

final class EventService extends CrudService implements IEventService
{
    /**
     * @param IEventRepository $repository
     * @param IRecurringPatternService $recurringPatternService ,
     */
    public function __construct(
        IEventRepository                          $repository,
        private readonly IRecurringPatternService $recurringPatternService,
    )
    {
        $this->repository = $repository;
    }

    /**
     * @param array $attributes
     *
     * @return Paginator
     */
    public function index(array $attributes): Paginator
    {
        return (new EventIndexer($this->repository))->run($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return EventModel
     * @throws Exception|Throwable
     */
    public function create(array $attributes): EventModel
    {
        return (new EventCreator($this->recurringPatternService, $this->repository))->run($attributes);
    }

    /**
     * @param mixed $model
     * @param array $attributes
     *
     * @return EventModel
     * @throws Exception|Throwable
     */
    public function update(Model $model, array $attributes): EventModel
    {
        return (new EventUpdater($this->repository, $this->recurringPatternService))->run($model, $attributes);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @param string|null $repeatUntil
     *
     * @return bool
     */
    public function checkOverlapping(string $startDate, string $endDate, ?string $repeatUntil = null): bool
    {
        $eventDto = DateDto::from(['start' => $startDate, 'end' => $endDate, 'repeat_until' => $repeatUntil,]);

        return $this->repository->checkOverlapping($eventDto->toArray());
    }

    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return Builder
     */
    public function filterEventsWithFromToQuery(string $startDate, string $endDate): Builder
    {
        return $this->repository->filterEventsWithFromToQuery($this->repository->query(), $startDate, $endDate);
    }

    /**
     * @param Model $model
     *
     * @return Model
     * @throws Throwable
     */
    public function delete(Model $model,): Model
    {
        /** @var EventModel $eventModel */
        $eventModel = $model;

        return (new EventDeleter($this->repository))->run($eventModel);
    }

    /**
     * @param Model $model
     * @param string $fieldName
     * @param mixed $data
     *
     * @return Model
     */
    public function patch(Model $model, string $fieldName, mixed $data): Model
    {
        return $this->repository->patch($model, $fieldName, $data);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return Collection
     */
    public function filterEventsWithFromTo(string $startDate, string $endDate): Collection
    {
        return $this->repository->filterEventsWithFromToQuery($this->repository->query(), $startDate, $endDate)->get();
    }
}
