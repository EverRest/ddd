<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Event\Enum\RecurringTypeEnum;
use App\Domain\Event\IRecurringTypeService;
use App\Infrastructure\Event\Service\Domain\RecurringTypeService;
use Illuminate\Database\Seeder;

class RecurringTypeSeeder extends Seeder
{
    /**
     * @param IRecurringTypeService $recurringTypeService
     */
    public function __construct(private readonly IRecurringTypeService $recurringTypeService)
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patterns = RecurringTypeEnum::values();
        foreach ($patterns as $pattern) {
            if ($this->recurringTypeService->getRecurringTypeByCode($pattern)) {
                continue;
            }
            $this->recurringTypeService->create(['recurring_type' => $pattern,]);
        }
    }
}
