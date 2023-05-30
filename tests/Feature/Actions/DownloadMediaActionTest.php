<?php

declare(strict_types=1);


use App\Actions\DecryptMessageAction;
use App\Actions\DownloadMediaAction;
use App\Actions\ShowMessageAction;
use App\Actions\StoreMessageAction;
use App\Data\MessageData;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\UploadedFile;
use Pest\Expectation;
use STS\ZipStream\ZipStream;

beforeEach(function (): void {
    Storage::fake();
});

it('return false if no media is associated', function (): void {

    $messageData = Message::factory()->create()->refresh()->getData();

    expect(app(DownloadMediaAction::class)->execute($messageData->id))->toBeFalse();
});

it('can return correct file names', function (): void {
    $file1 = UploadedFile::fake()->create('file1');
    $file2 = UploadedFile::fake()->create('file2');

    $media = [$file1, $file2];

    $message = $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $message = app(StoreMessageAction::class)->execute(MessageData::from($message), $media);
    $messageData = MessageData::from($message);

    app(DecryptMessageAction::class)->execute(
        $messageData->id,
        app(ShowMessageAction::class)->getMetaData()
    );

    $expectedNames = [
        Storage::path("{$messageData->decryptedMediaStoragePath}{$file1->hashName()}") => 'file1',
        Storage::path("{$messageData->decryptedMediaStoragePath}{$file2->hashName()}") => 'file2',
    ];

    $names = app(DownloadMediaAction::class)->getNames($messageData->id);

    expect($names)->each(function (Expectation $value, string $key) use ($expectedNames): void {
        $value->toBe($expectedNames[$key]);
    });

});

it('allows to download media associated with message', function (): void {
    $file1 = UploadedFile::fake()->create('file1');
    $file2 = UploadedFile::fake()->create('file2');

    $media = [$file1, $file2];

    $message = $data = Message::factory()
        ->withMessage()
        ->withTimeZone('Asia/Colombo')
        ->make()
        ->makeVisible('password')
        ->toArray();

    $message = app(StoreMessageAction::class)->execute(MessageData::from($message), $media);

    $messageData = MessageData::from($message);

    app(DecryptMessageAction::class)->execute(
        $messageData->id,
        app(ShowMessageAction::class)->getMetaData()
    );

    $results = app(DownloadMediaAction::class)->execute($messageData->id);
    $names = app(DownloadMediaAction::class)->getNames($messageData->id);

    expect($results)->toBeInstanceOf(ZipStream::class);
});
