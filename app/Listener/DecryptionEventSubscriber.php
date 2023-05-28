<?php

declare(strict_types=1);

namespace App\Listener;

use App\Events\DecryptionSucceeded;
use App\Models\Message;
use Illuminate\Events\Dispatcher;

class DecryptionEventSubscriber
{
    public function handleDecryptionSuccess(DecryptionSucceeded $event): void
    {
        $message = Message::findOrFail($event->id);

        $message->visits()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
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
