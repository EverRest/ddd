<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Actions;

use App\Domain\Event\IEventRepository;
use App\Infrastructure\Laravel\Model\EventModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class EventDeleter
{
    /**
     * @param IEventRepository $eventRepository
     */
    public function __construct(private readonly IEventRepository $eventRepository)
    {
    }

    /**
     * @param EventModel $eventModel
     *
     * @return EventModel
     * @throws Throwable
     */
    public function run(EventModel $eventModel): EventModel
    {
        DB::beginTransaction();
        try {
            $parent = $eventModel->parent ?? $eventModel;
            $this->eventRepository->destroy($parent);
            DB::commit();

            return $eventModel;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
