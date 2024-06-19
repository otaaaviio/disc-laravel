<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * @throws AuthException
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->is_super_admin === false) {
            throw AuthException::unauthorized();
        }

        return $next($request);
    }
}
