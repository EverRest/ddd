<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Event\Enum\RecurringTypeEnum;
use App\Infrastructure\Event\Task\CreateRecurringTypeByCode;
use App\Infrastructure\Event\Task\GetRecurringTypeByCode;
use Illuminate\Database\Seeder;

class RecurringTypeSeeder extends Seeder
{
    /**
     * @param GetRecurringTypeByCode $getRecurringTypeByCode
     * @param CreateRecurringTypeByCode $createRecurringTypeByCode
     */
    public function __construct(
        private readonly  GetRecurringTypeByCode $getRecurringTypeByCode,
        private readonly CreateRecurringTypeByCode $createRecurringTypeByCode,
    ) {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patterns = RecurringTypeEnum::values();
        foreach ($patterns as $pattern) {
            if ($this->getRecurringTypeByCode->run($pattern)) {
                continue;
            }
            $this->createRecurringTypeByCode->run($pattern);
        }
    }
}
