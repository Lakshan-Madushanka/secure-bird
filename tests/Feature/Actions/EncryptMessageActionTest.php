<?php

declare(strict_types=1);


use App\Actions\StoreMessageAction;
use App\Data\MessageData;
use App\Models\Message;

use function Pest\Laravel\assertDatabaseCount;

it('will encrypt text message', function (): void {
    Storage::fake();

    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    $createdMessage = app(StoreMessageAction::class)->execute($messageDto);
    $createdMessage->refresh();

    expect(Storage::exists($createdMessage->textStoragePath))
        ->toBeTrue()
        ->and($createdMessage->text_path)
        ->toBe($createdMessage->textStoragePath);

    assertDatabaseCount('messages', 1);
});
