<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Adm User',
            'email' => 'adm@admin.com',
            'password' => bcrypt('password'),
            'is_super_admin' => true,
        ]);

        $normal_user = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'usr@user.com',
            'password' => bcrypt('password'),
            'is_super_admin' => false,
        ]);

        for ($i = 0; $i < 2; $i++) {
            $guild = Guild::factory()->create();
            $channels = Channel::factory(5)->recycle($guild)->create();

            if ($i == 0) {
                $guild->members()->attach($admin->id, ['role' => Role::Admin]);
                $guild->members()->attach($normal_user->id, ['role' => Role::Member]);
            } else {
                $guild->members()->attach($admin->id, ['role' => Role::Member]);
                $guild->members()->attach($normal_user->id, ['role' => Role::Admin]);
            }

            $users = [$admin, $normal_user];

            foreach ($users as $user) {
                Message::factory(10)
                    ->recycle($channels)
                    ->recycle($user)
                    ->create();
            }
        }
    }
}
