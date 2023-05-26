<?php

declare(strict_types=1);

use App\Events\DataChunkDecrypted;
use App\Events\DecryptionSucceeded;
use App\Support\Decryptor;
use App\Support\Encryptor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake();
    Event::fake();
});

it('can decrypt a text', function (): void {
    $id = Str::random();
    $text = fake()->paragraphs(5, true);
    $path = Str::random().'/';

    $encryptor = Encryptor::create($id, $path);
    $encryptor->setText($text);
    $encryptor->encrypt();

    $decryptor = Decryptor::create($id, $path);
    $decryptor->decrypt();

    expect($decryptor->getProgress())->toBe(100);

    Event::assertDispatched(DataChunkDecrypted::class);
    Event::assertDispatched(DecryptionSucceeded::class);

    Event::assertDispatched(fn (DecryptionSucceeded $decryptionSucceeded) => $text === $decryptionSucceeded->data->text);
});

it('can decrypt a media', function (): void {
    $id = Str::random();
    $originalFileName = 'file.jpg';
    $path = 'test/';
    $decryptedPath = 'test/original/';

    $originalFilePath = UploadedFile::fake()->image($originalFileName)->size(10)->storeAs($path, $originalFileName);
    $originalFileHash = sha1_file(Storage::path($originalFilePath));

    $encryptor = Encryptor::create($id, 'test/');
    $encryptor->setMediaPath('test/');
    $encryptor->encrypt();

    $decryptor = Decryptor::create($id, $path);
    $decryptor->setMediaPath($path);
    $decryptor->setDecryptedMediaPath($decryptedPath);
    $decryptor->decrypt();

    $decryptedFileHash = sha1_file(Storage::path($decryptedPath.$originalFileName));

    expect($decryptedFileHash)->toBe($originalFileHash)
        ->and($decryptor->getProgress())->toBe(100);

    Event::assertDispatched(DataChunkDecrypted::class);
    Event::assertDispatched(DecryptionSucceeded::class);
});
