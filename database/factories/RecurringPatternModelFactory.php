<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Infrastructure\Laravel\Model\RecurringPatternModel;
use App\Infrastructure\Laravel\Model\RecurringTypeModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringTypeModel>
 */
class RecurringPatternModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecurringPatternModel::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recurring_type_id' => RecurringTypeModel::first(),
            'repeat_until' => fake()->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'day_of_week' => fake()->numberBetween(1, 7),
            'day_of_month' => fake()->numberBetween(1, 12),
            'week_of_month' => fake()->numberBetween(1, 4),
            'month_of_year' => fake()->numberBetween(1, 12),
        ];
    }
}
