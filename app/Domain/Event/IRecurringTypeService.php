<?php

namespace App\Domain\Event;

use App\Domain\Shared\ICrudService;
use Illuminate\Database\Eloquent\Model;

interface IRecurringTypeService
{
    /**
     * @param string $code
     *
     * @return ?Model
     */
    public function getRecurringTypeByCode(string $code): ?Model;
}
