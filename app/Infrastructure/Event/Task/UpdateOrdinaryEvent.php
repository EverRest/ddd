<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Task;

use App\Domain\Event\Aggregate\UpdateEventData;
use App\Domain\Event\IEventRepository;
use App\Domain\Shared\ITask;
use App\Infrastructure\Event\Trait\HasRemoveEmptyValuesFromArray;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class UpdateOrdinaryEvent implements ITask
{
    use HasRemoveEmptyValuesFromArray;

    /**
     * @var IEventRepository $eventRepository
     */
    private IEventRepository $eventRepository;

    public function __construct()
    {
        $this->eventRepository = App::make(IEventRepository::class);
    }

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return Model
     */
    public function run(Model $model, array $attributes): Model
    {
        $updateEventData = UpdateEventData::from($attributes);
        $data = $this->removeEmptyValues($updateEventData->toArray());

        return $this->eventRepository->update($model, $data);
    }
}
