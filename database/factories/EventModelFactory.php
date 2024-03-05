<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Infrastructure\Laravel\Model\EventModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<EventModel>
 */
class EventModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeThisYear()
            ->format('Y-m-d H:i:s');
        $end = Carbon::parse($start)->addHours(2)
            ->format('Y-m-d H:i:s');
        return [
            'parent_id' => null,
            'recurring_pattern_id' => RecurringPatternModelFactory::new()
                ->create(),
            'title' => fake()->title,
            'description' => fake()->text(300),
            'start' => $start,
            'end' => $end,
        ];
    }
}
