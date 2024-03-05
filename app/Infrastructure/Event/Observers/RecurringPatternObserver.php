<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Observers;

use App\Infrastructure\Event\Trait\ObserverMustDropModelCache;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;

class RecurringPatternObserver
{
    use ObserverMustDropModelCache;

    /**
     * Handle the RecurringPatternModel "created" event.
     *
     * @param  RecurringPatternModel  $eventModel
     *
     * @return void
     */
    public function created(RecurringPatternModel $eventModel): void
    {
        $this->dropModelCache();
    }

    /**
     * Handle the RecurringPatternModel "updated" event.
     *
     * @param RecurringPatternModel $recurringPatternMode
     *
     * @return void
     */
    public function updated(RecurringPatternModel $recurringPatternModel): void
    {
        $this->dropModelCache();
    }

    /**
     * Handle the RecurringPatternModel "deleted" event.
     */
    public function deleted(RecurringPatternModel $eventModel): void
    {
        $this->dropModelCache();
    }
}
