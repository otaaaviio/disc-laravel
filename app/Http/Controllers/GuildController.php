<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuildRequest;
use App\Interfaces\Services\IGuildService;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class GuildController extends Controller
{
    protected IGuildService $guildService;

    public function __construct(IGuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    // only super admin can access
    public function getAllGuilds(): JsonResponse
    {
        $guildsResource = $this->guildService->getAllGuilds();

        return response()->json(['guilds' => $guildsResource], StatusCode::HTTP_OK);
    }

    public function index(): JsonResponse
    {
        $guildsResource = $this->guildService->getUserGuilds();

        return response()->json(['guilds' => $guildsResource], StatusCode::HTTP_OK);
    }

    public function show(Guild $guild): JsonResponse
    {
        $guildResource = $this->guildService->getGuild($guild);

        return response()->json(['guild' => $guildResource], StatusCode::HTTP_OK);
    }

    public function update(Guild $guild, StoreGuildRequest $request): JsonResponse
    {
        $data = $request->validated();

        $guildResource = $this->guildService->upsertGuild($data, $guild);

        return response()->json([
            'message' => 'Guild successfully updated',
            'guild' => $guildResource,
        ], StatusCode::HTTP_OK);
    }

    public function store(StoreGuildRequest $request): JsonResponse
    {
        $data = $request->validated();

        $guildResource = $this->guildService->upsertGuild($data);

        return response()->json([
            'message' => 'Guild successfully created',
            'guild' => $guildResource,
        ], StatusCode::HTTP_CREATED);
    }

    public function destroy(Guild $guild): JsonResponse
    {
        $this->guildService->delete($guild);

        return response()->json([
            'message' => 'Guild successfully deleted',
        ], StatusCode::HTTP_OK);
    }

    public function getInviteCode(Guild $guild): JsonResponse
    {
        $inviteCode = $this->guildService->getInviteCode($guild);

        return response()->json(['invite_code' => $inviteCode], StatusCode::HTTP_OK);
    }

    public function leave(Guild $guild): JsonResponse
    {
        $this->guildService->leaveGuild($guild);

        return response()->json(['message' => 'Leave Successfully'], StatusCode::HTTP_OK);
    }

    public function entryByInviteCode(Request $request): JsonResponse
    {
        $request->validate(['invite_code' => 'required|string']);

        $invite_code = $request->input('invite_code');

        $guildResource = $this->guildService->entryByInviteCode($invite_code);

        return response()->json([
            'message' => 'Successfully entered into the guild',
            'guild' => $guildResource,
        ], StatusCode::HTTP_OK);
    }
}
