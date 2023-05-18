<?php

declare(strict_types=1);


use App\Http\Livewire\MessageForm;

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
