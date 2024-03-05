<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Trait;

use Illuminate\Support\Facades\Artisan;

trait ObserverMustDropModelCache
{
    /**
     * @return void
     */
    protected function dropModelCache(): void
    {
        Artisan::call("modelCache:clear");
    }
}
