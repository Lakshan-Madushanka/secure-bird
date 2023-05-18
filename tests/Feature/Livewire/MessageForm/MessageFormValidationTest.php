<?php

declare(strict_types=1);

use App\Http\Livewire\MessageForm;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

use function Pest\Livewire\livewire;

it('text_field_is_required', function (): void {
    $component = livewire(MessageForm::class)
        ->set('text', '')
        ->call('submit')
        ->assertHasErrors(['text' => 'required']);
});

it('password_field_is_required', function (): void {
    $component = livewire(MessageForm::class)
        ->set('password', '')
        ->call('submit')
        ->assertHasErrors(['password' => 'required']);
});

it('text_no_of_allowed_visits_field_must_be_greater_than_0', function (): void {
    $component = Livewire::test(MessageForm::class)
        ->set('no_of_allowed_visits', (int) 0)
        ->call('submit')
        ->assertHasErrors(['no_of_allowed_visits' => 'min:1']);
});

it('text_expires_at_field_must_be_after_the_current_time', function (): void {
    $component1 = Livewire::test(MessageForm::class)
        ->set('expires_at', now()->subMinute()->toISOString())
        ->call('submit')
        ->assertHasErrors(['expires_at' => 'after']);

    $component2 = Livewire::test(MessageForm::class)
        ->set('expires_at', now()->addMinutes(5)->toISOString())
        ->call('submit')
        ->assertHasNoErrors(['expires_at' => 'after']);
});

it('text_uploaded_media_size_must_not_exceed_25_megabytes', function (): void {
    $media = [
        UploadedFile::fake()->create(Str::random(5), 25000),
        UploadedFile::fake()->create(Str::random(5), 25000),
    ];

    $component2 = livewire(MessageForm::class)
        ->set('media', $media)
        ->call('submit')
        ->assertHasNoErrors(['media' => 'max:25000']);
});
