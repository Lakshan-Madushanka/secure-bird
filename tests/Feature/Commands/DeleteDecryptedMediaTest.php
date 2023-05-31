<?php

declare(strict_types=1);


use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;

it('return message if no of visits not exceeded', function (): void {
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

    Artisan::call('message:delete-decrypted-media');

    $messages->each(function (Message $message): void {
        expect(Storage::directoryExists($message->decryptedMediaStoragePath))->toBeFalse();
    });
});
