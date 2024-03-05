<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestApi;

class CreateTest extends TestApi
{
    /**
     * @var string|null
     */
    protected ?string $routeName = 'events.store';

    /**
     * @var string $method
     */
    protected string $method = 'POST';

    /**
     * @test
     */
    public function testSuccessStoreEvent()
    {
        $data = $this->getTestData();
        $route = route($this->routeName,);
        $this->json($this->method, $route, $data)
            ->assertStatus(ResponseAlias::HTTP_CREATED);
        $this->assertDatabaseHas('events', $data);
    }

    /**
     * @test
     */
    public function testFailStoreEvent()
    {
        $data = $this->getIncorrectTestData();
        $this->json($this->method, route($this->routeName,), $data)
            ->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseCount('events', 0);
    }

    /**
     * @return mixed
     */
    protected function getTestData(): mixed
    {
        $start = Carbon::tomorrow();
        return [
            'title' => fake()->title,
            'description' => fake()->text(300),
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $start->clone()
                ->addHours(2)
                ->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return mixed
     */
    protected function getIncorrectTestData(): mixed
    {
        $data = $this->getTestData();
        return Arr::set(
            $data, 'start',
            Carbon::yesterday()
                ->format('Y-m-d H:i:s')
        );
    }
}
