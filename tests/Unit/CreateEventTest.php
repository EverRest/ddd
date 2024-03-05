<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Infrastructure\Event\Task\CreateEvent;
use App\Infrastructure\Laravel\Model\EventModel;
use Database\Factories\RecurringPatternModelFactory;
use Illuminate\Support\Carbon;
use Tests\TestFeature;

class CreateEventTest extends TestFeature
{
    /**
     * @var CreateEvent $createEvent
     */
    private CreateEvent $createEvent;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->createEvent = $this->app
            ->make(CreateEvent::class);
    }

    /**
     * @test
     */
    public function testCreateEventTest()
    {
        $count = EventModel::count();
        $patternData = $this->getTestData();
        $model = $this->createEvent->run($patternData);
        $this->assertModelExists($model);
        $this->assertDatabaseCount('events', ++$count);
    }

    /**
     * @return mixed
     */
    protected function getTestData(): mixed
    {
        $start = fake()->dateTime->format('Y-m-d H:i:s');
        return  [
            'title' => fake()->title,
            'description' => fake()->text(300),
            'parent_id' => 1,
            'recurring_pattern_id' => RecurringPatternModelFactory::new()->create(),
            'start' => $start,
            'end' => Carbon::parse($start)
                ->addHour()->format('Y-m-d H:i:s',)
        ];
    }
}
