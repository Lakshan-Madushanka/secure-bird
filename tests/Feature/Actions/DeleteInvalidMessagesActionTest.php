<?php

declare(strict_types=1);


use App\Actions\StoreMessageAction;
use App\Data\MessageData;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

it('can delete invalid messages', function (): void {
    Message::factory()
        ->count(2)
        ->create(['expires_at' => now()->addMinute()]);

    Message::factory()
        ->count(3)
        ->create(['expires_at' => now()->subMinute(5)]);

    Message::factory()
        ->hasVisits(2)
        ->count(1)
        ->create(['no_of_allowed_visits' => 1]);

    Message::factory()
        ->hasVisits(2)
        ->count(1)
        ->create([
            'no_of_allowed_visits' => 1,
            'expires_at' => now()->subMinutes(5)
        ]);

    $noOfDeletedResults = app(\App\Actions\DeleteInvalidMessagesAction::class)->execute();

    expect($noOfDeletedResults)->toBe(5);
});

it('can delete storage associated with anD invalid message', function (): void {
    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    $createdMessage = app(StoreMessageAction::class)->execute($messageDto);
    $createdMessage->expires_at = now()->subMinute(5);
    $createdMessage->save();
    $createdMessage->refresh();

    $createdMessageData = MessageData::from($createdMessage);

    $noOfDeletedResults = app(\App\Actions\DeleteInvalidMessagesAction::class)->execute();

    expect($noOfDeletedResults)->toBe(1)
        ->and(Storage::exists($createdMessage->storagePath))->toBeFalse();
});
