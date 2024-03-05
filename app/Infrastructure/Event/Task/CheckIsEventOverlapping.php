<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Shared\ITask;

class CheckIsEventOverlapping implements ITask
{
    /**
     * @param string $start
     * @param string $end
     * @param string|null $repeat_until
     *
     * @return bool
     */
    public function run(string $start, string $end, ?string $repeat_until): bool
    {
        return $start < $end;
    }
}
