<?php

namespace Database\Factories;

use App\Enums\ProfileStatusEnum;
use App\Models\Administrator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'status' => ProfileStatusEnum::ACTIVE,
            'image' => null,
            'administrator_id' => Administrator::all()->first()->id,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProfileStatusEnum::INACTIVE,
        ]);
    }
}
