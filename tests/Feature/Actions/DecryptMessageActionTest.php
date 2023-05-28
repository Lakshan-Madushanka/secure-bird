<?php

declare(strict_types=1);


use App\Actions\DecryptMessageAction;
use App\Actions\StoreMessageAction;
use App\Data\MessageData;
use App\Events\DecryptionSucceeded;
use App\Models\Message;

use App\Models\Visit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\assertDatabaseCount;

it('can decrypt message with associated media', function (): void {
    Storage::fake();
    Event::fake();

    $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $messageDto = MessageData::from($data);

    $file1 = UploadedFile::fake()->create('file1', 5);
    $file2 = UploadedFile::fake()->create('file2', 10);

    $media = [
        $file1,
        $file2,
    ];

    $createdMessage = app(StoreMessageAction::class)->execute($messageDto, $media);
    $createdMessage->refresh();

    $messageData = MessageData::from($createdMessage);

    app(DecryptMessageAction::class)->execute($messageData->id);

    Event::assertDispatched(fn (
        DecryptionSucceeded $decryptionSucceeded
    ) => $messageDto->text === $decryptionSucceeded->data->text);

    Event::assertDispatched(function (DecryptionSucceeded $decryptionSucceeded) use (
        $messageData,
        $media,
        $file1,
        $file2,
    ) {
        $decryptedPath = $decryptionSucceeded->data->decrptedMediaPath;

        $originalFile1Hash = sha1($file1->getContent());
        $decryptedFile1Hash = sha1(Storage::get($decryptedPath.$file1->hashName()));

        $originalFile2Hash = sha1($file2->getContent());
        $decryptedFile2Hash = sha1(Storage::get($decryptedPath.$file2->hashName()));

        return ($originalFile1Hash === $decryptedFile1Hash) && ($originalFile2Hash === $decryptedFile2Hash);
    });

});

it('create a record in visits table after successful decryption', function (): void {
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

    $messageData = MessageData::from($createdMessage);

    app(DecryptMessageAction::class)->execute($messageData->id);

    assertDatabaseCount('visits', 1);

    $visitData = Visit::first()->getData();

    expect($visitData->message_id)->toBe($messageData->id)
        ->and($visitData->ip_address)->toBe(request()->ip())
        ->and($visitData->user_agent)->toBe(request()->userAgent());

});
