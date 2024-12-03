<?php

declare(strict_types=1);


use App\Actions\SendMessageVisitedMailAction;
use App\Mail\MessageVisited;
use App\Models\Message;
use App\Models\Visit;

it('wont send mail to the owner if reference mail is empty', function (): void {
    Mail::fake();

    $message = Message::factory()
        ->has(Visit::factory()->count(2))
        ->create([
            'password' => 'password',
            'reference_mail' => null,
        ]);

    app(SendMessageVisitedMailAction::class)->execute($message);

    Mail::assertNotSent(MessageVisited::class);
});

it('send mail to the owner after message is visited', function (): void {
    Mail::fake();

    $message = Message::factory()
        ->has(Visit::factory()->count(2))
        ->create(['password' => 'password']);

    app(SendMessageVisitedMailAction::class)->execute($message);

    Mail::assertSent(MessageVisited::class, 1);
});
