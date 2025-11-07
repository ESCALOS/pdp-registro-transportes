<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1,
            'document_type' => fake()->randomElement([1, 2]),
            'document_number' => fake()->numerify('########'),
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'license_number' => fake()->numerify('Q########'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('9########'),
            'status' => fake()->randomElement([1, 2, 3, 4, 5, 6]),
            'appeal_token' => null,
            'appeal_token_expires_at' => null,
        ];
    }
}
