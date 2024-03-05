<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Provider;

use App\Domain;
use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Event\IRecurringTypeRepository;
use App\Infrastructure;
use App\Infrastructure\Event\Repository\EventRepository;
use App\Domain\Event\IEventRepository;
use App\Infrastructure\Event\Repository\RecurringPatternRepository;
use App\Infrastructure\Event\Repository\RecurringTypeRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
