<?php

declare(strict_types=1);


use App\Actions\SendMessageVisitCompletedMailAction;
use App\Actions\SendMessageVisitedMailAction;
use App\Mail\MessageVisitCompleted;
use App\Mail\MessageVisited;
use App\Models\Message;
use App\Models\Visit;


it('wont send mail to the owner if message is not completed', function (): void {
    Mail::fake();

    $message = Message::factory()
        ->has(Visit::factory()->count(2))
        ->create([
            'password' => 'password',
        ]);

    app(SendMessageVisitCompletedMailAction::class)->execute($message);

    Mail::assertNotSent(MessageVisitCompleted::class);
});

it('send mail to the owner after message is completed', function (): void {
    Mail::fake();

    $message = Message::factory()
        ->has(Visit::factory()->count(2))
        ->create([
            'password' => 'password',
            'expires_at' => now()->subHour(),
        ]);

    app(SendMessageVisitCompletedMailAction::class)->execute($message);

    Mail::assertSent(MessageVisitCompleted::class, 1);
});


