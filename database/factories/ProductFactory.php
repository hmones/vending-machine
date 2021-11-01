<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_name'     => $this->faker->name,
            'seller_id'        => User::factory()->seller(),
            'amount_available' => $this->faker->numberBetween(0, 100),
            'cost'             => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
