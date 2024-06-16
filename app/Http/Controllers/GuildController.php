<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuildRequest;
use App\interfaces\Services\IGuildService;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use OpenApi\Attributes as OA;

class GuildController extends Controller
{
    protected IGuildService $guildService;

    public function __construct(IGuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    #[OA\Get(
        path: "/api/allGuilds",
        summary: "Get all guilds",
        tags: ["Admin"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function getAllGuilds(): JsonResponse
    {
        $guildsResource = $this->guildService->getAllGuilds();

        return response()->json(['guilds' => $guildsResource], StatusCode::HTTP_OK);
    }

    #[OA\Get(
        path: "/api/guilds",
        summary: "Get all guilds of authenticated user",
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function index(): JsonResponse
    {
        $guildsResource = $this->guildService->index();

        return response()->json(['guilds' => $guildsResource], StatusCode::HTTP_OK);
    }

    #[OA\Get(
        path: "/api/guilds/{guild}",
        summary: "Get a detailed guild",
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function show(Guild $guild): JsonResponse
    {
        $guildResource = $this->guildService->show($guild);
        return response()->json(['guild' => $guildResource], StatusCode::HTTP_OK);
    }

    #[OA\Put(
        path: "/api/guilds/{guild}",
        summary: "Update a guild",
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'name', description: "Guild name", type: "string"),
                        new OA\Property(property: 'description', description: "Guild description", type: "string"),
                        new OA\Property(property: 'icon_url', description: "Guild Icon Url", type: "string")]
                ))),
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_FORBIDDEN, description: "Forbidden"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function update(Guild $guild, StoreGuildRequest $request): JsonResponse
    {
        $data = $request->validated();

        $guildResource = $this->guildService->upsertGuild($data, $guild);

        return response()->json([
            'message' => 'Guild successfully updated',
            'guild' => $guildResource
        ], StatusCode::HTTP_OK);
    }

    #[OA\Post(
        path: "/api/guilds",
        summary: "Create a guild",
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: "application/json",
                schema: new OA\Schema(required: ["name", "description", "icon_url"],
                    properties: [
                        new OA\Property(property: 'name', description: "Guild name", type: "string"),
                        new OA\Property(property: 'description', description: "Guild description", type: "string"),
                        new OA\Property(property: 'icon_url', description: "Guild Icon Url", type: "string")]
                ))),
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_CREATED, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function store(StoreGuildRequest $request): JsonResponse
    {
        $data = $request->validated();

        $guildResource = $this->guildService->upsertGuild($data);

        return response()->json([
            'message' => 'Guild successfully created',
            'guild' => $guildResource
        ], StatusCode::HTTP_CREATED);
    }

    #[OA\Delete(
        path: "/api/guilds/{guild}",
        summary: "Delete a guild",
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_FORBIDDEN, description: "Forbidden"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function destroy(Guild $guild): JsonResponse
    {
        $this->guildService->delete($guild);

        return response()->json([
            'message' => 'Guild successfully deleted'
        ], StatusCode::HTTP_OK);
    }

    #[OA\Get(
        path: "/api/guilds/inviteCode/{guild}",
        summary: "Get invite code of a guild",
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function getInviteCode(Guild $guild): JsonResponse
    {
        $inviteCode = $this->guildService->getInviteCode($guild);

        return response()->json(['invite_code' => $inviteCode], StatusCode::HTTP_OK);
    }

    #[OA\Post(
        path: "/api/guilds/entry",
        summary: "Enter into a guild by invite code",
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: "application/json",
                schema: new OA\Schema(required: ["invite_code"],
                    properties: [
                        new OA\Property(property: 'invite_code', description: "Invite code", type: "string")]
                ))),
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function entryByInviteCode(Request $request): JsonResponse
    {
        $request->validate(['invite_code' => 'required|string']);

        $invite_code = $request->input('invite_code');

        $guildResource = $this->guildService->entryByInviteCode($invite_code);

        return response()->json([
            'message' => 'Successfully entered into the guild',
            'guild' => $guildResource
        ], StatusCode::HTTP_OK);
    }
}
