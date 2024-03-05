<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Infrastructure\Laravel\Model\EventModel;
use Database\Factories\EventModelFactory;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestApi;

class DeleteTest extends TestApi
{
    /**
     * @var string|null
     */
    protected  ?string $routeName = 'events.destroy';

    /**
     * @var string $method
     */
    protected string $method = 'DELETE';

    /**
     * @test
     */
    public function testSuccessDestroyEvent()
    {
        $eventModel =EventModelFactory::new()->create();
        $route = route( $this->routeName, ['event' => $eventModel]);
        $response = $this->json($this->method, $route);
        $response->assertStatus(ResponseAlias::HTTP_OK);
        $this->assertDatabaseMissing('events', ['id' => $eventModel->id]);
    }


    /**
     * @test
     */
    public function testIFailDestroyEvent()
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
