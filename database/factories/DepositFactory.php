<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => 0.05 * $this->faker->numberBetween(0, 10000)
        ];
    }
}
