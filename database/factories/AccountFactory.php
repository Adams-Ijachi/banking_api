<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "account_number" => $this->faker->unique()->randomNumber(8),
            "account_balance" => $this->faker->randomFloat(2, 0, 100),
            "account_type" => $this->faker->randomElement(["checking", "savings"]),
        ];
    }
}
