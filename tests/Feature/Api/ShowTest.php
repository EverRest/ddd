<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Database\Factories\EventModelFactory;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestApi;

class ShowTest extends TestApi
{
    /**
     * @var string|null
     */
    protected ?string $routeName = 'events.show';

    /**
     * @test
     */
    public function testSuccessShowEvent()
    {
        $eventModel = $this->getTestData();
        $route = route( $this->routeName, ['event' => $eventModel]);
        $response = $this->json($this->method, $route);
        $response->assertJsonStructure(['data' => ['id',  'frequency','title', 'description', 'start', 'end']]);
        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * @test
     */
    public function testFailShowEvent()
    {
        $model = EventModelFactory::new()->create();
        $deletedId = $model->id;
        $model->delete();
        $route = route( $this->routeName, ['event' => $deletedId]);
        $response = $this->json($this->method, $route);
        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * @return mixed
     */
    protected function getTestData(): mixed
    {
        return EventModelFactory::new()->create();
    }
}
