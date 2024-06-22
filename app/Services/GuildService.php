<?php

namespace App\Services;

use App\Enums\Role;
use App\Exceptions\GuildException;
use App\Http\Resources\GuildDetailedResource;
use App\Http\Resources\GuildResource;
use App\Interfaces\Services\IGuildService;
use App\Models\Guild;
use App\Models\GuildMember;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;
use Illuminate\Support\Str;

class GuildService implements IGuildService
{
    public function getAllGuilds(): AnonymousResourceCollection
    {
        return GuildResource::collection(Guild::all());
    }

    /**
     * @throws GuildException
     */
    public function getUserGuilds(): AnonymousResourceCollection
    {
        $guilds = Guild::whereHas('members', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        if ($guilds->isEmpty()) {
            throw GuildException::dontHaveGuildsToShow();
        }

        return GuildResource::collection($guilds);
    }

    /**
     * @throws GuildException
     */
    public function getGuild(Guild $guild): GuildDetailedResource
    {
        $guild = Guild::where('id', $guild->id)->whereHas('members', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();

        if (! $guild) {
            throw GuildException::notAGuildMemberException();
        }

        return GuildDetailedResource::make($guild);
    }

    /**
     * @throws GuildException
     */
    public function upsertGuild(array $data, Guild $guild = null): GuildResource
    {
        if (! $guild) {
            return $this->create($data);
        }

        return $this->update($guild, $data);
    }

    /**
     * @throws GuildException
     */
    public function getInviteCode(Guild $guild): string
    {
        $guild_member = GuildMember::where('user_id', auth()->id())
            ->where('guild_id', $guild->id)
            ->first();

        if (! $guild_member) {
            throw GuildException::notAGuildMemberException();
        }

        return $guild->invite_code;
    }

    /**
     * @throws GuildException
     */
    public function entryByInviteCode(string $invite_code): GuildResource
    {
        $guild = Guild::where('invite_code', $invite_code)->first();
        if (! $guild) {
            throw GuildException::invalidInviteCode();
        }

        $guild->members()->attach(auth()->id(), ['role' => Role::Member]);

        return GuildResource::make($guild);
    }

    private function create(array $data): GuildResource
    {
        $guild = Guild::create($data);
        $guild->invite_code = Str::random(8) . $guild->id;
        $guild->save();

        $guild->members()->attach(auth()->id(), ['role' => Role::Admin]);

        return GuildResource::make($guild);
    }

    /**
     * @throws GuildException
     */
    private function update(Guild $guild, array $data): GuildResource
    {
        $this->checkManagerPermission($guild->id, auth()->id());

        $guild->update($data);

        return GuildResource::make($guild);
    }

    /**
     * @throws Throwable
     */
    public function delete(Guild $guild): void
    {
        $this->checkManagerPermission($guild->id, auth()->id());

        $guild->delete();
    }

    /**
     * @throws GuildException
     */
    public function leaveGuild(Guild $guild): void
    {
        if (! $guild->members()->wherePivot('user_id', auth()->id())->exists()) {
            throw GuildException::notAGuildMemberException();
        }

        if ($this->checkRequestUserIsAdmin($guild)) {
            throw GuildException::adminCannotLeave();
        }

        $guild->members()->detach(auth()->id());
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

    private function checkRequestUserIsAdmin(Guild $guild): bool
    {
        $guildMember = $guild->members()->wherePivot('user_id', auth()->id())->first();

        return $guildMember && $guildMember->pivot->role === Role::Admin->value;
    }
}
