<?php

namespace Database\Factories;

use App\Models\Guild;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guild>
 */
class GuildFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'icon_url' => $this->faker->imageUrl(),
            'invite_code' => $this->faker->bothify('?????????'),
        ];
    }
}
