<?php

declare(strict_types=1);

namespace App\Listener;

use App\Actions\SendMessageVisitCompletedMailAction;
use App\Actions\SendMessageVisitedMailAction;
use App\Events\DecryptionSucceeded;
use App\Models\Message;
use Illuminate\Events\Dispatcher;

class DecryptionEventSubscriber
{
    public function handleDecryptionSuccess(DecryptionSucceeded $event): void
    {
        $message = Message::findOrFail($event->id);

        $visit = $message->visits()->create([
            ...$event->metaData,
        ]);

        $message->setRelation('visits', $visit);

        app(SendMessageVisitedMailAction::class)->execute($message);

        $message->unsetRelation('visits');
        app(SendMessageVisitCompletedMailAction::class)->execute($message);
    }

    /**
     * @param  Dispatcher  $events
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            DecryptionSucceeded::class => 'handleDecryptionSuccess',
        ];
    }

}
