<?php

declare(strict_types=1);

namespace App\Listener;

use App\Events\DataChunkEncrypted;
use App\Events\EncryptionFail;
use App\Events\EncryptionSucceeded;
use App\Models\Message;
use Illuminate\Events\Dispatcher;

class EncryptionEventSubscriber
{
    public function handleDataChunkEncrypted(DataChunkEncrypted $event): void
    {
        /** @var Message $model */
        $model = Message::whereId($event->id)->first();

        $model->encryption_progress = $event->percentage;

        $model->save();
    }

    public function handleEncryptionSuccess(EncryptionSucceeded $event): void
    {
        /** @var Message $model */
        $model = Message::whereId($event->id)->first();

        $model->encryption_progress = 100;
        $model->encryption_success = 1;
        $model->text_path = $event->textPath;

        $model->save();
    }

    public function handleEncryptionFail(EncryptionFail $event): void
    {
        /** @var Message $model */
        $model = Message::whereId($event->id)->first();

        $model->encryption_success = 0;

        $model->save();
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
