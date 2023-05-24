<?php

declare(strict_types=1);


use App\Data\MessageData;
use App\Models\Message;

it('return message if no of visits not exceeded', function (): void {
    $message = Message::factory()
        ->hasVisits(2)
        ->create(['no_of_allowed_visits' => 3])
        ->refresh();

    $messageData = MessageData::from($message);

    $result = Message::query()->visitsNotExceeded()->first();
    $resultData = MessageData::from($result);

    expect($result)->not()->toBeEmpty()
        ->and($resultData->id)->toBe($messageData->id);
});

it('return empty if no of visits exceeded', function (): void {
    $message = Message::factory()
        ->hasVisits(4)
        ->create(['no_of_allowed_visits' => 3]);

    $result = Message::query()->visitsNotExceeded()->get();

    expect($result)->toBeEmpty();
});

it('can filter expired messages', function (): void {
    $message = Message::factory()->create(['expires_at' => now()->subMinute()])->refresh();
    $messageData = MessageData::from($message);

    $result = Message::query()->expired()->first();
    $resultData = MessageData::from($result);

    expect($result)->not()->toBeEmpty()
        ->and($resultData->id)->toBe($messageData->id);
});

it('can filter not expired messages', function (): void {
    $message = Message::factory()->create(['expires_at' => now()->addMinute()])->refresh();
    $messageData = MessageData::from($message);

    $result = Message::query()->notExpired()->first();
    $resultData = MessageData::from($result);

    expect($result)->not()->toBeEmpty()
        ->and($resultData->id)->toBe($messageData->id);
});

it('can filter valid messages', function (): void {
    $validMessages = Message::factory()
        ->count(2)
        ->create(['expires_at' => now()->addMinute()]);

    $results = Message::query()->valid()->get();

    expect($results)->toHaveCount(2);
});

it('can filter invalid messages', function (): void {
    $message = Message::factory()
        ->hasVisits(4)
        ->create(['no_of_allowed_visits' => 3]);

    $results = Message::query()->valid()->get();

    expect($results)->toHaveCount(0);
});
