<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Channel>
 */
class ChannelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'guild_id' => Guild::factory(),
        ];
    }
}
