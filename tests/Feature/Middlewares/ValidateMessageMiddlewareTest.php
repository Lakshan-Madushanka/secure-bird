<?php

declare(strict_types=1);


use App\Http\Middleware\ValidateMessage;
use App\Models\Message;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

use function Pest\Laravel\getJson;

it('throws route not found exception for invalid message id', function (): void {
    $messageId = Str::uuid()->toString();

    $request = Request::create(
        uri: route('messages.show', ['messageId' => $messageId]),
        parameters: ['messageId' => $messageId]
    );

    $middleware = new ValidateMessage();
    $requestSucceeded = false;

    $middleware->handle($request, static function () use (&$requestSucceeded) {
        $requestSucceeded = true;
        return new \Symfony\Component\HttpFoundation\Response();
    });
})->throws(NotFoundHttpException::class);

it('allows to proceed route with valid message id', function (): void {
    $messageId = Message::factory()->create(['expires_at' => now()->addMinute(5)])->id;

    getJson(route('messages.show', ['messageId' => $messageId]));
})->throwsNoExceptions();
