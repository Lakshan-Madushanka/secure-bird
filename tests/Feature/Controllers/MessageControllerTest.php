<?php

declare(strict_types=1);


use App\Http\Controllers\MessagesController;
use App\Models\Message;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('shows not found page for invalid message id', function (): void {
    $messageId = Str::uuid()->toString();

    $route = action([MessagesController::class, 'show'], ['messageId' => $messageId]);

    $response = get($route);

    $response->assertNotFound();
    $response->assertSee('404');
    $response->assertSee('Not Found');
});

it('allows to proceed to controller action if message id is valid', function (): void {
    $messageId = Message::factory()->create(['expires_at' => now()->addMinute(5)])->id;

    $route = action([MessagesController::class, 'show'], ['messageId' => $messageId]);
})->throwsNoExceptions();
