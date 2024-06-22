<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Interfaces\Services\IMessageService;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class MessageController extends Controller
{
    protected IMessageService $messageService;

    public function __construct(IMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function store(Guild $guild, Channel $channel, StoreMessageRequest $request): JsonResponse
    {
        $data = $request->validated();

        $messageResource = $this->messageService->sendMessage($guild, $channel, $data);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $messageResource,
        ], StatusCode::HTTP_CREATED);
    }

    public function destroy(Guild $guild, Channel $channel, Message $message): JsonResponse
    {
        $this->messageService->deleteMessage($guild, $channel, $message);

        return response()->json([
            'message' => 'Message deleted successfully',
        ], StatusCode::HTTP_OK);
    }
}
