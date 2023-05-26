<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Message;
use App\Support\Decryptor;

class DecryptMessageAction
{
    /**
     * @param  string  $messageId
     * Relative path to storage
     * @return void
     */
    public function execute(
        string $messageId,
    ): void {
        $messageData = Message::findOrFail($messageId)->getData();

        $decryptor = Decryptor::create($messageId, (string) $messageData->storagePath);

        $decryptor->setMediaPath((string) $messageData->mediaStoragePath);
        $decryptor->setTextPath((string) $messageData->textStoragePath);
        $decryptor->setDecryptedMediaPath((string) $messageData->decryptedMediaStoragePath);

        $decryptor->decrypt();

    }
}
