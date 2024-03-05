<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Database\Factories\EventModelFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestApi;

class UpdateTest extends TestApi
{
    /**
     * @var string|null
     */
    protected  ?string $routeName = 'events.update';

    /**
     * @var string $method
     */
    protected string $method = 'PUT';
    /**
     * @test
     */
    public function testSuccessUpdateEvent()
    {
        $eventModel = EventModelFactory::new()->create();
        $route = route( $this->routeName, ['event' => $eventModel]);
        $response = $this->json($this->method, $route, $this->getTestData());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data' => ['id',  'frequency','title', 'description', 'start', 'end']]);
        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * @test
     */
    public function testFailUpdateEvent()
    {
        $eventModel = EventModelFactory::new()->create();
        $route = route($this->routeName, [
            'event' => $eventModel,
        ]);
        $response = $this->json($this->method, $route, $this->getIncorrectData());
        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return mixed
     */
    protected function getTestData(): mixed
    {
        return[
            'title' => fake()->text(100),
//            'description' => fake()->text(100),
        ];
    }

    /**
     * @return mixed
     */
    protected function getIncorrectData(): mixed
    {
        return[
            'frequency' => 'daily',
            'end' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'start' => Carbon::yesterday()->format('Y-m-d H:i:s'),
        ];
    }
}
