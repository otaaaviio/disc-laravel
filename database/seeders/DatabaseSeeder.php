<?php

namespace Database\Seeders;

use App\enums\Role;
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
            'name' => 'Adm User',
            'email' => 'adm@admin.com',
            'password' => bcrypt('password'),
            'is_super_admin' => true,
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

        // attach some users to guilds
        foreach ($guilds as $guild) {
            $usersToAttach = $users->random(rand(1, 10));
            $userAdmin = $usersToAttach->random();

            $guild->members()->attach($usersToAttach, ['role' => Role::Member]);
            $guild->members()->attach($userAdmin, ['role' => Role::Admin]);
        }
    }
}
