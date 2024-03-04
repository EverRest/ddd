<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Observers;

use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Support\Facades\Artisan;

class EventObserver
{
    /**
     * @var string[] $cachedModels
     */
    protected array $cachedModels = [
        'App\\Infrastructure\\Laravel\Model\\EventModel',
        'App\\Infrastructure\\Laravel\Model\\RecurringPatternModel',
        'App\\Infrastructure\\Laravel\Model\\RecurringTypeModel',
    ];

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

    /**
     * @return void
     */
    private function dropModelCache(): void
    {
        foreach ($this->cachedModels as $cachedModel) {
            Artisan::call("modelCache:clear $cachedModel");
        }
    }
}
