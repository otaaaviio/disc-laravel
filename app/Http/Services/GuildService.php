<?php

namespace App\Http\Services;

use App\Exceptions\GuildException;
use App\Http\Resources\GuildDetailedResource;
use App\Http\Resources\GuildResource;
use App\interfaces\Repositories\IGuildRepository;
use App\interfaces\Services\IGuildService;
use App\Models\Guild;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class GuildService implements IGuildService
{
    protected IGuildRepository $guildRepository;

    public function __construct(IGuildRepository $guildRepository)
    {
        $this->guildRepository = $guildRepository;
    }

    public function getAllGuilds(): AnonymousResourceCollection
    {
        $guild = $this->guildRepository->all();
        return GuildResource::collection($guild);
    }

    public function index(): AnonymousResourceCollection
    {
        $user_id = auth()->user()->id;
        $guilds = $this->guildRepository->getGuildsByUserId($user_id);
        return GuildResource::collection($guilds);
    }

    /**
     * @throws GuildException
     */
    public function show(Guild $guild): GuildDetailedResource
    {
        $user_id = auth()->user()->id;
        $guild = $this->guildRepository->getGuild($guild, $user_id);

        if(!$guild)
            throw GuildException::notFound();

        return GuildDetailedResource::make($guild);
    }

    public function upsertGuild(array $data, Guild $guild = null): GuildResource
    {
        if (!$guild)
            return $this->create($data);
        return $this->update($guild, $data);
    }

    public function getInviteCode(Guild $guild): string
    {
        $user_id = auth()->user()->id;
        return $this->guildRepository->getInviteCode($guild, $user_id);
    }

    public function entryByInviteCode(string $invite_code): GuildResource
    {
        $user_id = auth()->user()->id;
        $guild = $this->guildRepository->entryByInviteCode($invite_code, $user_id);
        return GuildResource::make($guild);
    }

    private function create(array $data): GuildResource
    {
        $user_id = auth()->user()->id;
        $guild = $this->guildRepository->create($data, $user_id);
        return GuildResource::make($guild);
    }

    private function update(Guild $guild, array $data): GuildResource
    {
        $user_id = auth()->user()->id;
        $updated_guild = $this->guildRepository->update($guild, $data, $user_id);
        return GuildResource::make($updated_guild);
    }

    /**
     * @throws Throwable
     */
    public function delete(Guild $guild): void
    {
        $user_id = auth()->user()->id;
        $this->guildRepository->delete($guild, $user_id);
    }
}
