<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\IRecurringTypeRepository;
use App\Domain\Shared\ITask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CreateRecurringTypeByCode implements ITask
{
    /**
     * @var IRecurringTypeRepository $recurringTypeRepository
     */
    private IRecurringTypeRepository $recurringTypeRepository;

    /**
     * CreateRecurringTypeByCode constructor.
     */
    public function __construct()
    {
        $this->recurringTypeRepository = App::make(IRecurringTypeRepository::class);
    }

    /**
     * @param string $code
     * @return Model
     *
     */
    public function run(string $code): Model
    {
        return $this->recurringTypeRepository->store(['recurring_type' => $code]);
    }
}
