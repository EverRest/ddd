<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Provider;

use App\Infrastructure\Event\Observers\EventObserver;
use App\Infrastructure\Event\Observers\RecurringPatternObserver;
use App\Infrastructure\Laravel\Model\EventModel;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        EventModel::observe(EventObserver::class);
        RecurringPatternModel::observe(RecurringPatternObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
