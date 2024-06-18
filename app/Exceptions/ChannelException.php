<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Throwable;

class ChannelException extends Exception
{
    public function __construct($message = '', $code = StatusCode::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function dontHaveManagerPermission(): self
    {
        return new self(
            'You are not allowed to manager this channel',
            StatusCode::HTTP_UNAUTHORIZED
        );
    }

    public static function channelDoesNotBelongToGuild(): self
    {
        return new self(
            'The specified channel does not belong to the provided guild',
            StatusCode::HTTP_UNAUTHORIZED
        );
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
