<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Service\Domain;

use App\Domain\Event\IRecurringPatternService;
use App\Domain\Shared\CrudService;
use App\Infrastructure\Event\Repository\RecurringPatternRepository;

final class RecurringPatternsService extends CrudService implements IRecurringPatternService
{
    /**
     * @param RecurringPatternRepository $recurringPatternRepository
     */
    public function __construct(RecurringPatternRepository $recurringPatternRepository)
    {
        $this->repository = $recurringPatternRepository;
    }
}
