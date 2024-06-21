<?php

namespace App\interfaces\Services;

use App\Http\Resources\GuildDetailedResource;
use App\Http\Resources\GuildResource;
use App\Models\Guild;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface IGuildService
{
    public function getAllGuilds(): AnonymousResourceCollection;

    public function index(): AnonymousResourceCollection;

    public function show(Guild $guild): GuildDetailedResource;

    public function upsertGuild(array $data, ?Guild $guild = null): GuildResource;

    public function getInviteCode(Guild $guild): string;

    public function leaveGuild(Guild $guild): void;

    public function entryByInviteCode(string $invite_code): GuildResource;

    public function delete(Guild $guild): void;
}
