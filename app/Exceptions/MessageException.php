<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Throwable;

class MessageException extends Exception
{
    public function __construct($message = '', $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, StatusCode::HTTP_UNAUTHORIZED, $previous);
    }

    public static function dontHavePermissionToDeleteMessage(): self
    {
        return new self(
            'You do not have permission to delete this message',
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
