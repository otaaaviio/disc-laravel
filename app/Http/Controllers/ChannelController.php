<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\MessageResource;
use App\interfaces\Services\IChannelService;
use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class ChannelController extends Controller
{
    protected IChannelService $channelService;

    public function __construct(IChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    public function join(Guild $guild, Channel $channel): JsonResponse
    {
        $this->channelService->joinChannel($guild, $channel);

        return response()->json([
            'message' => 'Successfully joined channel '.$channel->name,
            'messages' => MessageResource::collection($channel->messages),
        ], StatusCode::HTTP_OK);
    }

    public function store(Guild $guild, StoreChannelRequest $request): JsonResponse
    {
        $data = $request->validated();

        $channelResource = $this->channelService->upsert($data, $guild);

        return response()->json([
            'message' => 'Channel successfully created in guild '.$guild->name,
            'channel' => $channelResource,
        ], StatusCode::HTTP_CREATED);
    }

    public function update(Guild $guild, Channel $channel, UpdateChannelRequest $request): JsonResponse
    {
        $data = $request->validated();

        $channelResource = $this->channelService->upsert($data, $guild, $channel);

        return response()->json([
            'message' => 'Channel successfully updated',
            'channel' => $channelResource,
        ], StatusCode::HTTP_OK);
    }

    public function destroy(Guild $guild, Channel $channel): JsonResponse
    {
        $this->channelService->delete($guild, $channel);

        return response()->json([
            'message' => 'Channel successfully deleted',
        ], StatusCode::HTTP_OK);
    }
}
