<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Infrastructure\Event\Task\DeleteEvent;
use Database\Factories\EventModelFactory;
use Tests\TestFeature;

class DeleteEventTest extends TestFeature
{

    /**
     * @var DeleteEvent
     */
    private DeleteEvent $deleteEvent;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteEvent = $this->app
            ->make(DeleteEvent::class);
    }

    /**
     * @test
     */
    public function testDeleteEvent()
    {
        $event = $this->getTestData();
        $this->deleteEvent->run($event);
        $this->assertModelMissing($event);
    }

    /**
     * @return mixed
     */
    protected function getTestData(): mixed
    {
        return EventModelFactory::new()->create();
    }
}
