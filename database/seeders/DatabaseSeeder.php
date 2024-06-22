<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (config('app.env') === 'local') {
            $this->call(LocalSeeder::class);
        }
    }
}
