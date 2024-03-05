<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Repository;

use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Shared\Repository;
use App\Infrastructure\Laravel\Model\RecurringPatternModel;

final class RecurringPatternRepository extends Repository implements IRecurringPatternRepository
{
    /**
     * @var string  $model
     */
    protected string $model = RecurringPatternModel::class;
}
