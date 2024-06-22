<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Guild;
use App\Models\GuildMember;
use App\Models\User;

class GuildPolicy
{
    public function view(User $user, Guild $guild): bool
    {
        return $guild->members->contains($user->id);
    }

    public function updateOrDelete(User $user, Guild $guild): bool
    {
        return $guild->isUserAdminInGuild($user->id);
    }

    public function manageChannels(User $user, Guild $guild): bool
    {
        $guild_member = GuildMember::where('user_id', $user->id)->where('guild_id', $guild->id)->first();

        return $guild_member &&
            ($guild_member->role == Role::Admin->value || $guild_member->role == Role::Moderator->value);
    }
}
