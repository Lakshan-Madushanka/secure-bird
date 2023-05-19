<?php

declare(strict_types=1);


use App\Actions\UploadMediaAction;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\UploadedFile;

it('it can store media for a message', function (): void {
    Storage::fake();

    $fileName = 'test';
    $file = UploadedFile::fake()->create($fileName);
    $media = [$file];

    $message = Message::factory()->create();

    app(UploadMediaAction::class)->execute($media, $message);

    $destination = $message->storagePath.'/'.$file->hashName();

    Storage::disk()->assertExists($destination);
});

it('it can create a media record in db for a message', function (): void {

    Storage::fake();

    $fileName = 'test';
    $file = UploadedFile::fake()->create($fileName);
    $media = [$file];

    $message = Message::factory()->create();

    app(UploadMediaAction::class)->execute($media, $message);

    $destination = $message->storagePath.'/'.$file->hashName();

    Storage::disk()->assertExists($destination);

    /** @var \App\Data\MediaData $mediaRecord */
    $mediaRecord = $message->media()->first()?->getData();

    expect($mediaRecord->name)->toBe($file->hashName());
    expect($mediaRecord->original_name)->toBe($fileName);
    expect($mediaRecord->full_path)->toBe($destination);

});
