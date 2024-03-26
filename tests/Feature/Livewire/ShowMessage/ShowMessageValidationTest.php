<?php

declare(strict_types=1);

use App\Http\Livewire\ShowMessage;

use function Pest\Livewire\livewire;

test('password field is required', function (): void {
    $component = livewire(ShowMessage::class, ['messageId' => 'abcde'])
        ->set('password', '')
        ->call('show')
        ->assertHasErrors(['password' => 'required']);
});
