<?php
declare(strict_types=1);

namespace App\Infrastructure\Event\Service\Domain;

use App\Domain\Event\IRecurringTypeRepository;
use App\Domain\Event\IRecurringTypeService;
use App\Domain\Shared\CrudService;
use Illuminate\Database\Eloquent\Model;

final class RecurringTypeService extends CrudService implements IRecurringTypeService
{
    /**
     * @param IRecurringTypeRepository $recurringTypeRepository
     */
    public function __construct(IRecurringTypeRepository $recurringTypeRepository)
    {
        $this->repository = $recurringTypeRepository;
    }

    /**
     * @param string $code
     *
     * @return ?Model
     */
    public function getRecurringTypeByCode(string $code): ?Model
    {
        return $this->repository->getRecurringTypeByCode($code);
    }
}
