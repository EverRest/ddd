<?php

namespace App\Infrastructure\Laravel\Provider;

use App\Domain;
use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Event\IRecurringPatternService;
use App\Domain\Event\IRecurringTypeRepository;
use App\Domain\Event\IRecurringTypeService;
use App\Infrastructure;
use App\Infrastructure\Event\Repository\EventRepository;
use App\Domain\Event\IEventRepository;
use App\Domain\Event\IEventService;
use App\Infrastructure\Event\Repository\RecurringPatternRepository;
use App\Infrastructure\Event\Repository\RecurringTypeRepository;
use App\Infrastructure\Event\Service\Domain\EventService;
use App\Infrastructure\Event\Service\Domain\RecurringPatternsService;
use App\Infrastructure\Event\Service\Domain\RecurringTypeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories:
        $this->app->singleton(IEventRepository::class, EventRepository::class);
        $this->app->singleton(IRecurringPatternRepository::class, RecurringPatternRepository::class);
        $this->app->singleton(IRecurringTypeRepository::class, RecurringTypeRepository::class);
        // Services:
        $this->app->singleton(IRecurringPatternService::class, RecurringPatternsService::class);
        $this->app->singleton(IEventService::class, EventService::class);
        $this->app->singleton(IRecurringTypeService::class, RecurringTypeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
