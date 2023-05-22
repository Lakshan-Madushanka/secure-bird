<?php

declare(strict_types=1);


use App\Actions\EncryptMessageAction;
use App\Data\MessageData;

use App\Jobs\ProcessMessageEncryption;
use App\Models\Message;

beforeEach(function (): void {
    Storage::fake();
});

it('process the message encryption action', function (): void {
    $message = MessageData::from(
        Message::factory()
            ->create()
            ->refresh()
            ->makeVisible('password')
    );

    $messageEncryptAction = Mockery::mock(EncryptMessageAction::class);
    $messageEncryptAction->shouldReceive('execute')->once();

    $processMessageEncryptionJob = new ProcessMessageEncryption(
        $messageEncryptAction,
        $message->id,
        $message->text,
        $message->storagePath,
        $message->textStoragePath,
        $message->mediaStoragePath
    );

    $processMessageEncryptionJob->handle();
});
