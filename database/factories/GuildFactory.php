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
            'name' => '',
            'description' => $this->faker->sentence,
            'icon_url' => $this->faker->imageUrl(),
        ];
    }

    public function configure(): GuildFactory
    {
        return $this->afterCreating(function (Guild $guild) {
            $guild->name = 'Guild ' . $guild->id;
            $guild->invite_link = '/guilds/' . $guild->id;
            $guild->save();
        });
    }
}
