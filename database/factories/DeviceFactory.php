<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mac' => $mac = fake()->unique()->macAddress(),
            'name' => "device_{$mac}",
            'description' => random_int(0, 1) ? fake()->sentence() : null,
            'enabled' => random_int(0, 1) ? true : false,
            'valid_from' => random_int(0, 1) ? fake()->date() : null,
            'valid_to' => random_int(0, 1) ? fake()->date() : null,
        ];
    }
}
