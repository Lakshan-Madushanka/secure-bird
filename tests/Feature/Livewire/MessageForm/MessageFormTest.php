<?php

declare(strict_types=1);


use App\Http\Livewire\MessageForm;

use Illuminate\Http\UploadedFile;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Livewire\livewire;

it('it can store message', function (): void {
    livewire(MessageForm::class)
        ->set('text', 'some text')
        ->set('password', 'password')
        ->set('expires_at', now()->addMinutes(5)->toISOString())
        ->call('submit');

    assertDatabaseCount('messages', 1);
});

it('it can store media with message', function (): void {
    Storage::fake();

    $fileName = 'test';
    $file = UploadedFile::fake()->create($fileName);
    $media = [$file];

    livewire(MessageForm::class)
        ->set('text', 'some text')
        ->set('password', 'password')
        ->set('expires_at', now()->addMinutes(5)->toISOString())
        ->set('media', $media)
        ->call('submit');

    $message = \App\Models\Message::query()->latest()->first();

    $destination = $message?->storagePath.'/'.$file->hashName();

    assertDatabaseCount('messages', 1);
});
