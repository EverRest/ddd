<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enum;

use App\Infrastructure\Event\Trait\EnumHasToArray;

enum ListRequestEnum: string
{
    use EnumHasToArray;
    case searchKey = 'search';
    case limitKey = 'limit';
    case pageKey = 'page';
    case sortKey = 'sort';
    case orderKey = 'order';
}
