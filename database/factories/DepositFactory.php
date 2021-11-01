<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomElement([0.05, 0.1, 0.2, 0.5, 1]),
        ];
    }
}
