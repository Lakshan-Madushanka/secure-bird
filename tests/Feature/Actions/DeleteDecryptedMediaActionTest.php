<?php

declare(strict_types=1);


use App\Actions\DeleteDecryptedMediaAction;
use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function (): void {
    Storage::fake();
});

it('can delete decrypted messages media visited longer than 1h', function (): void {
    $file = UploadedFile::fake()->create(Str::random());

    // Created decrypted media that has visited before 1h
    for ($i = 0; $i < 1; $i++) {
        $messages = Message::factory()
            ->hasVisits(random_int(1, 5), [
                'created_at' => now()->subMinutes(62),
            ])
            ->create(['encryption_success' => 1]);
    }

    $messages->each(function (Message $message) use ($file): void {
        $file->store($message->decryptedMediaStoragePath);
    });

    app(DeleteDecryptedMediaAction::class)->execute();

    $messages->each(function (Message $message): void {
        expect(Storage::directoryExists($message->decryptedMediaStoragePath))->toBeFalse();
    });
});

it('wont delete decrypted messages media visited longer than 1h', function (): void {

    $file = UploadedFile::fake()->create(Str::random());

    // Created decrypted media that hasn't visited before 1h
    for ($i = 0; $i < 1; $i++) {
        $messages = Message::factory()
            ->hasVisits(random_int(1, 5), [
                'created_at' => now()->subMinutes(50),
            ])
            ->create(['encryption_success' => 1]);
    }

    $messages->each(function (Message $message) use ($file): void {
        $file->store($message->decryptedMediaStoragePath);
    });

    app(DeleteDecryptedMediaAction::class)->execute();

    $messages->each(function (Message $message): void {
        expect(Storage::directoryExists($message->decryptedMediaStoragePath))->toBeTrue();
    });
});
