<?php

namespace App\interfaces\Repositories;

use App\Models\Guild;
use Illuminate\Database\Eloquent\Collection;

interface IGuildRepository
{
    public function all(): Collection;

    public function getGuildsByUserId(int $user_id): Collection;

    public function getGuild(Guild $guild, int $user_id): ?Guild;

    public function create(array $data, int $user_id): Guild;

    public function update(Guild $guild, array $data, int $user_id): Guild;

    public function getInviteCode(Guild $guild, int $user_id): string;

    public function entryByInviteCode(string $invite_code, int $user_id): Guild;

    public function delete(Guild $guild, int $user_id): void;
}
