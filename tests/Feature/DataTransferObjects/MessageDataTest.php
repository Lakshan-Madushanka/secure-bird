<?php

declare(strict_types=1);


use App\Data\MessageData;
use App\Models\Message;
use Carbon\Carbon;
use Database\Factories\MessageFactory;

it('it convert expires at to utc time when storing', function (): void {
    /**
     * @var $data MessageFactory
     */
    $factory = Message::factory();
    $timeZone = 'Asia/Colombo';

    $data = $factory
        ->withTimeZone($timeZone)
        ->make()
        ->makeVisible('password')
        ->toArray();


    $dto = MessageData::from($data);

    $userTime = Carbon::parse($data['expires_at']);

    $userUtcTime = $userTime->shiftTimezone($timeZone)->timezone('utc');

    expect($dto->expires_at->diffInSeconds($userUtcTime))->toBe(0);
});

it('it set no_of_allowed_visits to -1 is empty string is passed', function (): void {
    /**
     * @var $data MessageFactory
     */
    $factory = Message::factory();
    $timeZone = 'Asia/Colombo';

    $data = $factory
        ->withTimeZone($timeZone)
        ->make(['no_of_allowed_visits' => ''])
        ->makeVisible('password')
        ->toArray();

    $dto = MessageData::from($data);

    expect($dto->no_of_allowed_visits)->toBe(-1);
});
