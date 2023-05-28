<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isValid = Message::query()
            ->whereId($request->route('messageId'))
            ->valid()
            ->exists();

        if (! $isValid) {
            abort(404);
        }

        return $next($request);
    }
}
