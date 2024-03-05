<?php

declare(strict_types=1);

namespace App\EventInterface\Controller;

use App\Domain\Event\Aggregate\EventResource;
use App\Infrastructure\Event\Action\EventCreator;
use App\Infrastructure\Event\Action\EventDestroyer;
use App\Infrastructure\Event\Action\EventIndexer;
use App\Infrastructure\Event\Action\EventUpdater;
use App\Infrastructure\Event\Request\Index;
use App\Infrastructure\Event\Request\Store;
use App\Infrastructure\Event\Request\Update;
use App\Infrastructure\Laravel\Controller;
use App\Infrastructure\Laravel\Model\EventModel;
use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\JsonResponse;
use Throwable;

class EventController extends Controller
{
    /**
     * @param Dispatcher $dispatcher
     * @param EventIndexer $eventIndexer
     * @param EventCreator $eventCreator
     * @param EventUpdater $eventUpdater
     * @param EventDestroyer $eventDestroyer
     */
    public function __construct(
        Dispatcher $dispatcher,
        private readonly EventIndexer $eventIndexer,
        private readonly EventCreator $eventCreator,
        private readonly EventUpdater $eventUpdater,
        private readonly EventDestroyer $eventDestroyer,
    )
    {
        parent::__construct($dispatcher);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function index(Index $request): JsonResponse
    {
        $attributes = $request->validated();
        $list = $this->eventIndexer->run($attributes);

        return EventResource::collection($list)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function store(Store $request): JsonResponse
    {
        $attributes = $request->validated();
        $model = $this->eventCreator->run($attributes);

        return (new EventResource($model))->response();
    }

    /**
     * Put the specified resource in storage.
     *
     * @param Update $request
     * @param EventModel $event
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(Update $request, EventModel $event): JsonResponse
    {
        $attributes = $request->validated();
        $event = $this->eventUpdater->run($event, $attributes);

        return (new EventResource($event->refresh()))->response();
    }

    /**
     * Display the specified resource.
     *
     * @param EventModel $event
     *
     * @return JsonResponse
     */
    public function show(EventModel $event): JsonResponse
    {
        return (new EventResource($event))->response();
    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param EventModel $event
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(EventModel $event): JsonResponse
    {
        $event = $this->eventDestroyer->run($event);

        return (new EventResource($event))->response();
    }
}
