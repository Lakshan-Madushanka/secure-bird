<?php

declare(strict_types=1);


use App\Http\Livewire\CreateMessageForm;

use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Storage::fake();
});

it('can store message', function (): void {
    livewire(CreateMessageForm::class)
        ->set('text', 'some text')
        ->set('password', 'password')
        ->set('reference_mail', fake()->email())
        ->set('expires_at', now()->addMinutes(5)->toISOString())
        ->call('submit');

    assertDatabaseCount('messages', 1);
});

it('can store media with message', function (): void {
    $fileName = 'test';
    $file = UploadedFile::fake()->create($fileName);
    $media = [$file];

    livewire(CreateMessageForm::class)
        ->set('text', 'some text')
        ->set('password', 'password')
        ->set('reference_mail', fake()->email())
        ->set('expires_at', now()->addMinutes(5)->toISOString())
        ->set('media', $media)
        ->call('submit');

    $message = Message::query()->latest()->first();

    $destination = $message?->storagePath.'/'.$file->hashName();

    expect(Storage::exists($destination));

    assertDatabaseCount('messages', 1);
});
