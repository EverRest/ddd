<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\CreateRecurringPatternData;
use App\Domain\Event\IRecurringPatternRepository;
use App\Domain\Shared\ITask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class CreateRecurrentPattern implements ITask
{
    private const EXCEPT_ATTRIBUTES = ['title', 'description',];

    private IRecurringPatternRepository $recurringPatternRepository;

    public function __construct()
    {
        $this->recurringPatternRepository = App::make(IRecurringPatternRepository::class);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function run(array $attributes): Model
    {
        $recurringPatternDto = CreateRecurringPatternData::from(
            Arr::except($attributes, self::EXCEPT_ATTRIBUTES)
        );
        return $this->recurringPatternRepository
            ->store($recurringPatternDto->toArray());
    }
}
