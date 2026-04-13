<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName().' '.str_replace("'", '', fake()->lastName()),
            'uniqueid' => $id = fake()->unique()->safeEmail(),
            'email' => $id,
            'emails' => random_int(0, 1) ? "$id;".fake()->safeEmail() : null,
            'active' => random_int(0, 1) ? true : false,
        ];
    }
}
