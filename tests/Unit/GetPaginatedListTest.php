<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Shared\Enum\ListRequestEnum;
use App\Infrastructure\Event\Task\GetEventPaginatedList;
use Database\Factories\EventModelFactory;
use Tests\TestFeature;

class GetPaginatedListTest extends TestFeature
{
    /**
     * @var GetEventPaginatedList
     */
    private GetEventPaginatedList $getEventPaginatedList;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->getEventPaginatedList = $this->app
            ->make(GetEventPaginatedList::class);
    }

    /**
     * @test
     */
    public function testGetListPaginated()
    {
        EventModelFactory::new()->count(20)->create();
        $listData = [
            ListRequestEnum::pageKey->value => 1,
            ListRequestEnum::orderKey->value => 'asc',
            ListRequestEnum::sortKey->value => 'title',
            ListRequestEnum::limitKey->value => 10,
        ];
        $list = $this->getEventPaginatedList->run($listData);
        $this->assertNotEmpty($list);
    }

}
