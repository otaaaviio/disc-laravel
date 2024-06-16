<?php

namespace App\Http\Repositories;

use App\enums\Role;
use App\Exceptions\GuildException;
use App\interfaces\Repositories\IGuildRepository;
use App\Models\Guild;
use App\Models\GuildMember;
use Illuminate\Database\Eloquent\Collection;

class GuildRepository implements IGuildRepository
{
    public function all(): Collection
    {
        return Guild::all();
    }

    /**
     * @throws GuildException
     */
    public function getGuildsByUserId(int $user_id): Collection
    {
        $guilds = Guild::whereHas('members', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->get();

        if ($guilds->isEmpty()) {
            throw GuildException::dontHaveGuildsToShow();
        }

        return $guilds;
    }

    public function getGuild(Guild $guild, int $user_id): ?Guild
    {
        return Guild::where('id', $guild->id)->whereHas('members', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->first();
    }

    /**
     * @throws GuildException
     */
    public function getInviteCode(Guild $guild, int $user_id): string
    {
        $guild_member = GuildMember::where('user_id', $user_id)->where('guild_id', $guild->id)->first();
        if (! $guild_member) {
            throw GuildException::notAGuildMemberException();
        }

        return $guild->invite_code;
    }

    /**
     * @throws GuildException
     */
    public function entryByInviteCode(string $invite_code, int $user_id): Guild
    {
        $guild = Guild::where('invite_code', $invite_code)->first();
        if (! $guild) {
            throw GuildException::invalidInviteCode();
        }

        $guild->members()->attach($user_id, ['role' => Role::Member]);

        return $guild;
    }

    public function create(array $data, int $user_id): Guild
    {
        $guild = Guild::create($data);
        $guild->members()->attach($user_id, ['role' => Role::Admin]);

        return $guild;
    }

    /**
     * @throws GuildException
     */
    public function update(Guild $guild, array $data, int $user_id): Guild
    {
        $this->checkManagerPermission($guild->id, $user_id);

        $guild->update($data);

        return $guild;
    }

    /**
     * @throws GuildException
     */
    public function delete(Guild $guild, int $user_id): void
    {
        $this->checkManagerPermission($guild->id, $user_id);

        $guild->delete();
    }

    /**
     * @throws GuildException
     */
    private function checkManagerPermission(int $guild_id, int $user_id): void
    {
        $guild_member = GuildMember::where('user_id', $user_id)->where('guild_id', $guild_id)->first();
        if (! $guild_member || $guild_member->role !== Role::Admin->value) {
            throw GuildException::dontHaveManagerPermission();
        }
    }
}
