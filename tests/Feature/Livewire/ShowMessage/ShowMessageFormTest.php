<?php

declare(strict_types=1);

use App\Http\Livewire\ShowMessageForm;
use App\Models\Message;
use App\Rules\MessageRule;

use function Pest\Livewire\livewire;

test('password field is required', function (): void {
    livewire(ShowMessageForm::class)
        ->set('messageId', '')
        ->call('show')
        ->assertHasErrors(['messageId' => 'required']);
});

test('requires valid message id', function (): void {
    livewire(ShowMessageForm::class)
        ->set('messageId', 'id')
        ->call('show')
        ->assertHasErrors(['messageId' => MessageRule::class]);
});

test('redirect to show message route for the valid id', function (): void {
    $message = Message::factory()->create();

    livewire(ShowMessageForm::class)
        ->set('messageId', $message->getKey())
        ->call('show')
        ->assertHasNoErrors()
        ->assertRedirect(route('messages.show', ['messageId' => $message->getKey()]));
});
