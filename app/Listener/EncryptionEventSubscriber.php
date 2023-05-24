<?php

declare(strict_types=1);

namespace App\Listener;

use App\Data\MessageData;
use App\Events\DataChunkEncrypted;
use App\Events\EncryptionFail;
use App\Events\EncryptionSucceeded;
use App\Models\Message;
use App\Notifications\MessageEncryptionSucceeded;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class EncryptionEventSubscriber
{
    public function handleDataChunkEncrypted(DataChunkEncrypted $event): void
    {
        /** @var Message $message */
        $message = Message::whereId($event->id)->first();

        $message->encryption_progress = $event->percentage;

        $message->save();
    }

    public function handleEncryptionSuccess(EncryptionSucceeded $event): void
    {
        /** @var Message $message */
        $message = Message::whereId($event->id)->first();

        $message->encryption_progress = 100;
        $message->encryption_success = 1;
        $message->text_path = $event->textPath;

        $message->save();

        $messageData = MessageData::from($message);

        $this->sendSuccessNotification($messageData->reference_mail, (string) $messageData->url);

    }

    public function handleEncryptionFail(EncryptionFail $event): void
    {
        /** @var Message $message */
        $message = Message::whereId($event->id)->first();

        $message->encryption_success = 0;

        $message->save();
    }

    public function sendSuccessNotification(?string $mail, string $url): void
    {
        if ($mail) {
            Notification::route(
                'mail',
                $mail,
            )->notify(new MessageEncryptionSucceeded($url));
        }
    }

    /**
     * @param  Dispatcher  $events
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            DataChunkEncrypted::class => 'handleDataChunkEncrypted',
            EncryptionSucceeded::class => 'handleEncryptionSuccess',
            EncryptionFail::class => 'handleEncryptionFail',

        ];
    }

}
