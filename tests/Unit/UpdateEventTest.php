<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Infrastructure\Event\Task\UpdateOrdinaryEvent;
use Database\Factories\EventModelFactory;
use Tests\TestFeature;

class UpdateEventTest extends TestFeature
{
    /**
     * @var UpdateOrdinaryEvent
     */
    private UpdateOrdinaryEvent $updateOrdinaryEvent;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->updateOrdinaryEvent = $this->app
            ->make(UpdateOrdinaryEvent::class);
    }

    /**
     * @test
     */
    public function tesUpdateOrdinaryEvent()
    {
        $model = EventModelFactory::new()->create();
        $title = fake()->title;
        $model = $this->updateOrdinaryEvent->run($model, ['title'=> $title]);
        $this->assertEquals($title, $model->title);
         }
}
