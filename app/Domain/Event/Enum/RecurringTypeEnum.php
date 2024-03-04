<?php

declare(strict_types=1);

namespace App\Domain\Event\Enum;

use App\Infrastructure\Event\Trait\EnumHasToArray;

enum RecurringTypeEnum: string
{
    use EnumHasToArray;

    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
//    case WEEKDAYS = 'weekdays';
//    case HOLIDAYS = 'holidays';
}
