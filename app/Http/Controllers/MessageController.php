<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\interfaces\Services\IMessageService;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class MessageController extends Controller
{
    protected IMessageService $messageService;

    public function __construct(IMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    #[OA\Post(
        path: '/api/guilds/{guild}/channels/{channel}/messages',
        summary: 'Send a message to a channel',
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: 'application/json',
                schema: new OA\Schema(required: ['content'],
                    properties: [
                        new OA\Property(property: 'content', description: 'Message content', type: 'string')]
                ))),
        tags: ['Authenticated'],
        responses: [
            new OA\Response(response: StatusCode::HTTP_CREATED, description: 'Successful operation'),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: 'Unauthorized'),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ]
    )]
    public function store(Guild $guild, Channel $channel, StoreMessageRequest $request): JsonResponse
    {
        $data = $request->validated();

        $messageResource = $this->messageService->sendMessage($guild, $channel, $data);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $messageResource,
        ], StatusCode::HTTP_CREATED);
    }

    #[OA\Delete(
        path: '/api/guilds/{guild}/channels/{channel}/messages/{message}',
        summary: 'Delete a message from a channel',
        tags: ['Authenticated'],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: 'Successful operation'),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: 'Unauthorized'),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ]
    )]
    public function destroy(Guild $guild, Channel $channel, Message $message): JsonResponse
    {
        $this->messageService->delete($guild, $channel, $message);

        return response()->json([
            'message' => 'Message deleted successfully',
        ], StatusCode::HTTP_OK);
    }
}
