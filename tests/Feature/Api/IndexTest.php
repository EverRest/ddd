<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Infrastructure\Laravel\Model\EventModel;
use Database\Factories\EventModelFactory;
use Illuminate\Http\Response;
use Tests\TestApi;

class IndexTest extends TestApi
{
    private const INDEX_EXPECTED_COUNT = 3;
    /**
     * @var string|null
     */
    protected ?string $routeName = 'events.index';

    /**
     * @test
     */
    public function testIndexEvent()
    {
        $this->getTestData();
        $route = route( $this->routeName, ['page' => 2, 'limit' => self::INDEX_EXPECTED_COUNT]);
        $response = $this->json($this->method, $route);
        $response->assertStatus(200);
        $response->assertJsonCount(self::INDEX_EXPECTED_COUNT);
    }

    /**
     * @test
     */
    public function testOrderedIndexEvent()
    {
        $this->getTestData();
        $route = route( $this->routeName);
        $response = $this->json($this->method, $route);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(self::INDEX_EXPECTED_COUNT);
    }

    /**
     * @test
     */
    public function testPaginatedIndexEvent()
    {
        $this->getTestData();
        $route = route($this->routeName);
        $response = $this->json($this->method, $route);
        $firstPageData = $response->json();
        $route = route( $this->routeName, ['page' => 2]);
        $secondPageData = $this->json($this->method, $route);
        $this->assertNotEquals($firstPageData, $secondPageData);
    }

    /**
     * @test
     */
    public function testSearchIndexEvent()
    {
        EventModel::all()->each(fn($model) => $model->delete());
        EventModelFactory::new()->count(20)->create();
        $event = EventModel::first();
        $route = route( $this->routeName, ['search' => $event->title]);
        $response = $this->json($this->method, $route);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function testFilteredIndexEvent()
    {
        $data = $this->getTestData();
        $response = $this->json($this->method, route($this->routeName, $data));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(self::INDEX_EXPECTED_COUNT);
    }

    /**
     * @return mixed
     */
    protected function getTestData(): mixed
    {
        return  [
            'from' => fake()->dateTimeThisYear,
            'to' => fake()->dateTimeThisYear,
        ];
    }
}
