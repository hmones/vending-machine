<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username'       => $this->faker->unique()->userName(),
            'role'           => $this->faker->randomElement(User::ROLES),
            'password'       => Hash::make('password'),
            'deposit'        => $this->faker->randomFloat(2, 0, 500),
            'remember_token' => Str::random(10)
        ];
    }

    public function seller(): Factory
    {
        return $this->state(fn() => ['role' => User::SELLER]);
    }

    public function buyer(): Factory
    {
        return $this->state(fn () => ['role' => User::BUYER]);
    }
}
