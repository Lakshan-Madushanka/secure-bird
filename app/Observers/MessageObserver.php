<?php

declare(strict_types=1);

namespace App\Observers;

use App\Data\MessageData;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

class MessageObserver
{
    public function deleted(Message $message): void
    {
        $messageData = MessageData::from($message);
        Storage::deleteDirectory($message->storagePath);
    }
}
