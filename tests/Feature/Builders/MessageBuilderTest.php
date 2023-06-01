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

it('return message if no of visits equal to -1(default)', function (): void {
    $message = Message::factory()
        ->hasVisits(2)
        ->create(['no_of_allowed_visits' => -1])
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

it('return empty if no of visits equal to allocated no of visits', function (): void {
    $message = Message::factory()
        ->hasVisits(4)
        ->create(['no_of_allowed_visits' => 4]);

    $result = Message::query()->visitsNotExceeded()->get();

    expect($result)->toBeEmpty();
});

it('return message if no of visits exceeded', function (): void {
    $message = Message::factory()
        ->hasVisits(4)
        ->create(['no_of_allowed_visits' => 3]);

    $result = Message::query()->visitsExceeded()->get();

    expect($result)->toHaveCount(1);
});

it('return message if no of visits equal to allocated visits', function (): void {
    $message = Message::factory()
        ->hasVisits(4)
        ->create(['no_of_allowed_visits' => 4]);

    $result = Message::query()->visitsExceeded()->get();

    expect($result)->toHaveCount(1);
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

it('return message if expires_at is null', function (): void {
    $message = Message::factory()->create(['expires_at' => null])->refresh();
    $messageData = MessageData::from($message);

    $result = Message::query()->notExpired()->first();
    $resultData = MessageData::from($result);

    expect($result)->not()->toBeEmpty()
        ->and($resultData->id)->toBe($messageData->id);
});

it('consider message as valid  if expires_at is null and no_of_allowed_visits is -1', function (): void {
    $message = Message::factory()->hasVisits(2)->create([
        'expires_at' => null,
        'no_of_allowed_visits' => -1,
    ])->refresh();

    $messageData = MessageData::from($message);

    $result = Message::query()->valid()->first();
    $resultData = MessageData::from($result);

    expect($result)->not()->toBeEmpty()
        ->and($resultData->id)->toBe($messageData->id);
});

it('can filter valid messages', function (): void {
    $validMessages = Message::factory()
        ->count(2)
        ->create(['expires_at' => now()->addMinute()]);

    $invalidMessages = Message::factory()
        ->count(3)
        ->create(['expires_at' => now()->subMinute(5)]);

    $results = Message::query()->valid()->get();

    expect($results)->toHaveCount(2);
});

it('can filter valid messages when message id is given', function (): void {
    $validMessage = Message::factory()
        ->create([
            'expires_at' => now()->addMinute(),
            'no_of_allowed_visits' => -1,
        ])
        ->refresh()
        ->getData();

    $result1 = Message::query()->whereId(123)->valid()->get();
    $result2 = Message::query()->whereId($validMessage->id)->valid()->get();

    expect($result1)->toHaveCount(0)
        ->and($result2)->toHaveCount(1);
});

it('can filter invalid messages', function (): void {
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

    $results = Message::query()->inValid()->get();

    expect($results)->toHaveCount(4);
});

it('can filter invalid messages when message id is given', function (): void {
    $validMessage = Message::factory()
        ->create([
            'expires_at' => now()->subMinutes(5),
        ])
        ->refresh()
        ->getData();

    $result1 = Message::query()->whereId(123)->inValid()->get();
    $result2 = Message::query()->whereId($validMessage->id)->inValid()->get();

    expect($result1)->toHaveCount(0)
        ->and($result2)->toHaveCount(1);
});
