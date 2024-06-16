<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\interfaces\Services\IChannelService;
use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use OpenApi\Attributes as OA;

class ChannelController extends Controller
{
    protected IChannelService $channelService;

    public function __construct(IChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    #[OA\Post(
        path: "/api/guilds/{guild}/channels",
        summary: "Create a new channel in a guild",
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: "application/json",
                schema: new OA\Schema(required: ["name", "description"],
                    properties: [
                        new OA\Property(property: 'name', description: "Channel name", type: "string"),
                        new OA\Property(property: 'description', description: "Channel description", type: "string")]
                ))),
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_CREATED, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_FORBIDDEN, description: "Forbidden"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function store(Guild $guild, StoreChannelRequest $request): JsonResponse
    {
        $data = $request->validated();

        $channelResource = $this->channelService->upsert($data, $guild);

        return response()->json([
            'message' => 'Channel successfully created in guild ' . $guild->name,
            'channel' => $channelResource
        ], StatusCode::HTTP_CREATED);
    }

    #[OA\Put(
        path: "/api/guilds/{guild}/channels/{channel}",
        summary: "Update a channel in a guild",
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'name', description: "Channel name", type: "string"),
                        new OA\Property(property: 'description', description: "Channel description", type: "string")]
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
    public function update(Guild $guild, Channel $channel, UpdateChannelRequest $request): JsonResponse
    {
        $data = $request->validated();

        $channelResource = $this->channelService->upsert($data, $guild, $channel);

        return response()->json([
            'message' => 'Channel successfully updated',
            'channel' => $channelResource
        ], StatusCode::HTTP_OK);
    }

    #[OA\Delete(
        path: "/api/guilds/{guild}/channels/{channel}",
        summary: "Delete a channel in a guild",
        tags: ["Authenticated"],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: "Successful operation"),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: StatusCode::HTTP_FORBIDDEN, description: "Forbidden"),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function destroy(Guild $guild, Channel $channel): JsonResponse
    {
        $this->channelService->delete($guild, $channel);

        return response()->json([
            'message' => 'Channel successfully deleted',
        ], StatusCode::HTTP_OK);
    }
}
