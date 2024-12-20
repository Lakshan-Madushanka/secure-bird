<?php

declare(strict_types=1);


use App\Actions\ShowMessageAction;
use App\Actions\StoreMessageAction;
use App\Data\MessageData;
use App\Events\DecryptionSucceeded;
use App\Mail\MessageVisitCompleted;
use App\Mail\MessageVisited;
use App\Models\Message;
use App\Models\Visit;

use function Pest\Laravel\assertDatabaseCount;

it('can show message', function (): void {
    Event::fake();

    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make(['password' => 'password'])
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);
    $messageDto->password = 'password';
    $createdMessageData = app(StoreMessageAction::class)->execute($messageDto)->getData();

    app(ShowMessageAction::class)->execute('password', $createdMessageData->id);

    Event::assertDispatched(
        DecryptionSucceeded::class,
        fn (DecryptionSucceeded $decryptionSucceeded) => $decryptionSucceeded->data->text === $messageDto->text
    );
});

it('can set visits and send mails to owner', function (): void {
    Mail::fake();

    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make([
            'password' => 'password',
            'expires_at' => now()->subHour(),
        ])
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);
    $messageDto->password = 'password';
    $createdMessageData = app(StoreMessageAction::class)->execute($messageDto)->getData();

    app(ShowMessageAction::class)->execute('password', $createdMessageData->id);

    assertDatabaseCount('visits', 1);

    $visitsData = Visit::first()->getData();

    expect($visitsData->ip_address)->toBe(request()->ip())
        ->and($visitsData->user_agent)->toBe(request()->userAgent());

    Mail::assertSent(MessageVisited::class, 1);
    Mail::assertSent(MessageVisitCompleted::class, 1);
});
