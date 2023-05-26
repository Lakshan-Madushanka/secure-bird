<?php

declare(strict_types=1);

use App\Events\DataChunkEncrypted;
use App\Events\EncryptionSucceeded;
use App\Support\Encryptor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

beforeEach(function (): void {
    Storage::fake();
    Event::fake();
});

it('can encrypt a text', function (): void {
    $id = Str::random();
    $text = fake()->paragraphs(5, true);
    $path = Str::random().'/';

    $encryptor = Encryptor::create($id, $path);
    $encryptor->setText($text);
    $encryptor->encrypt();

    expect(Storage::exists($path))->toBeTrue()
        ->and($encryptor->getProgress())->toBe(100)
        ->and(Storage::get($path.'text'))->not()->toBe($text);

    Event::assertDispatched(DataChunkEncrypted::class);
    Event::assertDispatched(EncryptionSucceeded::class);
});

it('can encrypt media', function (): void {
    $id = Str::random();
    $path = 'test/media/';

    $media = createMedia($path);

    $originalContent = Storage::get($media[0]);

    $encryptor = Encryptor::create($id, $path);
    $encryptor->setMediaPath($path);
    $encryptor->encrypt();

    $encrptedContent = Storage::get($media[0]);

    expect($encryptor->getProgress())->toBe(100)
        ->and($encrptedContent)->not()->toBe($originalContent);

    Event::assertDispatched(DataChunkEncrypted::class);
    Event::assertDispatched(EncryptionSucceeded::class);
});

/**
 * @param  string  $path
 * @param  int  $noOfMedia
 * @return array<UploadedFile>
 */
function createMedia(string $path, int $noOfMedia = 3): array
{
    $media = [];

    for ($i = 0; $i < $noOfMedia; $i++) {
        $media[] = UploadedFile::fake()->create(Str::random())->size(100)->store($path);
    }

    return $media;
}
