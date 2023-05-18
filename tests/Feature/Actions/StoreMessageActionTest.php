<?php

declare(strict_types=1);


use App\Actions\StoreMessageAction;
use App\Data\MessageData;
use App\Models\Message;

use function Pest\Laravel\assertDatabaseCount;

it('it can store message', function (): void {
    $data = Message::factory()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    app(StoreMessageAction::class)->execute($messageDto);

    assertDatabaseCount('messages', 1);
});

it('it hash the password when store message', function (): void {
    $password = 'password';

    $data = Message::factory()
        ->withTimeZone('Asia/Colombo')
        ->make(['password' => $password])
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    $message = app(StoreMessageAction::class)->execute($messageDto);

    $messageData = MessageData::from($message->makeVisible('password'));

    expect($messageData->password)->not()->toEqual($password);

    assertDatabaseCount('messages', 1);
});
