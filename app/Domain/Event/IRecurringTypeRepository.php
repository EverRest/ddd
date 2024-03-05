<?php

namespace App\Domain\Event;

use App\Domain\Shared\IRepository;
use Illuminate\Database\Eloquent\Model;

interface IRecurringTypeRepository extends IRepository
{
    /**
     * @param string $code
     *
     * @return Model|null
     */
    public function getByCode(string $code): ?Model;
}
