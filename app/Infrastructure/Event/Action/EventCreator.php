<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Action;

use App\Domain\Shared\IAction;
use App\Infrastructure\Event\Task\CreateEvent;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class EventCreator implements IAction
{
    /**
     * @param array $attributes
     *
     * @return Model
     * @throws Exception|Throwable
     */
    public function run(array $attributes): Model
    {
        return (new CreateEvent())->run($attributes);
    }
}
