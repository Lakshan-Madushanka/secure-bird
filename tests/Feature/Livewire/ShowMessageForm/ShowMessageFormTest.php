<?php

declare(strict_types=1);

use App\Http\Livewire\ShowMessage;
use App\Models\Message;

use function Pest\Livewire\livewire;

test('password field is required', function (): void {
    $component = livewire(ShowMessage::class, ['messageId' => '1234'])
        ->set('password', '')
        ->call('show')
        ->assertHasErrors(['password' => 'required']);
});


test('can set the message when mounting', function (): void {
    $messageData = Message::factory()
        ->create(['expires_at' => now()->addMinute()])
        ->refresh()
        ->getData();

    $component = livewire(ShowMessage::class, ['messageId' => $messageData->id])
        ->call('show');

    expect($component->get('messageId'))->toBe($messageData->id);
});


test('shows validation error if password is incorrect', function (): void {
    $messageData = Message::factory()
        ->create(['expires_at' => now()->addMinute()])
        ->refresh()
        ->getData();

    $component = livewire(ShowMessage::class, ['messageId' => $messageData->id])
        ->set('password', 'password')
        ->call('show')
        ->assertHasErrors('password');
});
