<?php

declare(strict_types=1);

use App\Http\Livewire\MessageForm;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

use function Pest\Livewire\livewire;

test('text_field is required', function (): void {
    $component = livewire(MessageForm::class)
        ->set('text', '')
        ->call('submit')
        ->assertHasErrors(['text' => 'required']);
});

test('password_field is required', function (): void {
    $component = livewire(MessageForm::class)
        ->set('password', '')
        ->call('submit')
        ->assertHasErrors(['password' => 'required']);
});

test('no_of_allowed_visits field must be greater than 0', function (): void {
    $component = Livewire::test(MessageForm::class)
        ->set('no_of_allowed_visits', (int) 0)
        ->call('submit')
        ->assertHasErrors(['no_of_allowed_visits' => 'min:1']);
});

test('expires_at_field must be after the current time', function (): void {
    $component1 = Livewire::test(MessageForm::class)
        ->set('expires_at', now()->subMinute()->toISOString())
        ->call('submit')
        ->assertHasErrors(['expires_at' => 'after']);

    $component2 = Livewire::test(MessageForm::class)
        ->set('expires_at', now()->addMinutes(5)->toISOString())
        ->call('submit')
        ->assertHasNoErrors(['expires_at' => 'after']);
});

test('uploaded media size must not exceed 25 megabytes', function (): void {
    $media = [
        UploadedFile::fake()->create(Str::random(5), 25000),
        UploadedFile::fake()->create(Str::random(5), 25000),
    ];

    $component2 = livewire(MessageForm::class)
        ->set('media', $media)
        ->call('submit')
        ->assertHasNoErrors(['media' => 'max:25000']);
});

it('can validate reference_mail field', function (): void {
    $component = livewire(MessageForm::class)
        ->set('reference_mail', 'a')
        ->call('submit')
        ->assertHasErrors(['reference_mail' => 'email']);
});
