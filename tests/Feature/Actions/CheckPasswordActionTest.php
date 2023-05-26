<?php

declare(strict_types=1);


use App\Actions\CheckPasswordAction;
use App\Models\Message;
use Illuminate\Validation\ValidationException;

it('throws validation exception for invalid password', function (): void {
    app(CheckPasswordAction::class)->execute('password', 'password');
})->throws(ValidationException::class);

it('return true if password is correct', function (): void {
    $messageData = Message::factory()
        ->create(['password' => 'password'])
        ->refresh()
        ->getData();

    $isCorrectPassword = app(CheckPasswordAction::class)->execute('password', $messageData->password);

    expect($isCorrectPassword)->toBeTrue();
});
