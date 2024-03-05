<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Observers;

use App\Infrastructure\Event\Trait\ObserverMustDropModelCache;
use App\Infrastructure\Laravel\Model\EventModel;

class EventObserver
{
    use ObserverMustDropModelCache;

    /**
     * Handle the EventModel "created" event.
     *
     * @param  EventModel  $eventModel
     *
     * @return void
     */
    public function created(EventModel $eventModel): void
    {
        $this->dropModelCache();
    }

    /**
     * Handle the EventModel "updated" event.
     *
     * @param EventModel  $eventModel
     *
     * @return void
     */
    public function updated(EventModel $eventModel): void
    {
        $this->dropModelCache();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(EventModel $eventModel): void
    {
        $this->dropModelCache();
    }
}
