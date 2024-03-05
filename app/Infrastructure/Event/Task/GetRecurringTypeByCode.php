<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\IRecurringTypeRepository;
use App\Domain\Shared\ITask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class GetRecurringTypeByCode implements ITask
{
    /**
     * @var IRecurringTypeRepository $recurringTypeRepository
     */
    private IRecurringTypeRepository $recurringTypeRepository;

    /**
     *  GetRecurringTypeByCode constructor.
     */
    public function __construct()
    {
        $this->recurringTypeRepository = App::make(IRecurringTypeRepository::class);
    }

    /**
     * @param string $frequency
     *
     * @return Model|null
     */
    public function run(string $frequency): ?Model
    {
        return $this->recurringTypeRepository->getByCode($frequency);
    }
}
