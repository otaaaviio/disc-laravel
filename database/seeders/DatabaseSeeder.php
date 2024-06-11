<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(50)->create();
        $guilds = Guild::factory(20)->create();

        $channels = Channel::factory(100)
            ->recycle($guilds)
            ->create();

        Message::factory(100)
            ->recycle($channels)
            ->recycle($users)
            ->create();
    }
}
