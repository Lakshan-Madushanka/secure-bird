<?php

declare(strict_types=1);


use App\Actions\EncryptMessageAction;
use App\Actions\StoreMessageAction;
use App\Actions\UploadMediaAction;
use App\Data\MessageData;
use App\Jobs\ProcessMessageEncryption;
use App\Models\Message;
use App\Notifications\MessageEncryptionSucceeded;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseCount;

beforeEach(function (): void {
    Storage::fake();
    Notification::fake();
});

it('can store message', function (): void {
    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    $createdMessage = app(StoreMessageAction::class)->execute($messageDto);

    assertDatabaseCount('messages', 1);
});

it('hash the password when store message', function (): void {
    $password = 'password';

    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make(['password' => $password])
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    $message = app(StoreMessageAction::class)->execute($messageDto);
    $message->refresh();

    $messageData = MessageData::from($message->makeVisible('password'));

    expect($messageData->password)->not()->toEqual($password);

    assertDatabaseCount('messages', 1);
});

it('call the upload_media_action and encrypt_message_action', function (): void {
    Queue::fake();

    $message = MessageData::from(
        Message::factory()
            ->withMessage()
            ->withTimeZone('Asia/Colombo')
            ->make()
            ->makeVisible('password')
            ->toArray()
    );

    $uploadMediaAction = Mockery::mock(UploadMediaAction::class);
    $uploadMediaAction->shouldReceive('execute')->once();

    $encryptMediaAction = Mockery::mock(EncryptMessageAction::class);
    //$encryptMediaAction->shouldReceive('execute')->once();

    $storeMediaAction = app(StoreMessageAction::class, [
        'uploadMediaAction' => $uploadMediaAction,
        'encryptMessageAction' => $encryptMediaAction,
    ]);

    $storeMediaAction->execute($message);

    Queue::assertPushed(ProcessMessageEncryption::class);
});

it('can send a notification to notify url', function (): void {
    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $messageData = MessageData::from($data);

    $createdMessage = app(StoreMessageAction::class)->execute($messageData)->refresh();

    $createdMessageData = MessageData::from($createdMessage);


    Notification::assertSentOnDemand(
        MessageEncryptionSucceeded::class,
        function (MessageEncryptionSucceeded $notification) use ($createdMessageData) {
            $reflection = new ReflectionObject($notification);

            $property = $reflection->getProperty('url');
            $property->setAccessible(true);

            $url = $property->getValue($notification);

            return $url === $createdMessageData->url;
        }
    );
});
