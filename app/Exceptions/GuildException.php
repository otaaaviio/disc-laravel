<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Throwable;

class GuildException extends Exception
{
    public function __construct($message = '', $code = StatusCode::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function dontHaveManagerPermission(): self
    {
        return new self(
            'You are not allowed to manager this guild',
            StatusCode::HTTP_UNAUTHORIZED
        );
    }

    public static function dontHaveGuildsToShow(): self
    {
        return new self(
            'You do not have any guilds to show',
            StatusCode::HTTP_NOT_FOUND
        );
    }

    public static function invalidInviteCode(): self
    {
        return new self(
            'Invalid invite code',
            StatusCode::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function adminCannotLeave(): self
    {
        return new self(
            'Admin cannot leave the guild',
            StatusCode::HTTP_UNAUTHORIZED
        );
    }

    public static function notFound(): self
    {
        return new self(
            'Guild not found',
            StatusCode::HTTP_NOT_FOUND
        );
    }

    public static function notAGuildMemberException(): self
    {
        return new self(
            'You are not a member of this guild',
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
