<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Repository;

use App\Domain\Event\IRecurringTypeRepository;
use App\Domain\Shared\Repository;
use App\Infrastructure\Laravel\Model\RecurringTypeModel;
use Illuminate\Database\Eloquent\Model;

final class RecurringTypeRepository extends Repository implements IRecurringTypeRepository
{
    /**
     * @var string $model
     */
    protected string $model = RecurringTypeModel::class;

    /**
     * @param string $code
     *
     * @return Model|null
     */
    public function getByCode(string $code): ?Model
    {
        /** @var Model|null $model */
        $model = $this->query()
            ->where('recurring_type', $code)
            ->first();

        return $model;
    }
}
