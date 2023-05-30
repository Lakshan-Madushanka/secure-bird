<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Message;
use App\Support\Decryptor;
use Exception;

class DecryptMessageAction
{
    /**
     * @param  string  $messageId
     * Relative path to storage
     * @param  array<string,string|null>  $metaData
     * @return void
     * @throws Exception
     */
    public function execute(
        string $messageId,
        array $metaData = []
    ): void {
        $messageData = Message::findOrFail($messageId)->getData();

        $decryptor = Decryptor::create($messageId, (string) $messageData->storagePath);

        $decryptor->setMediaPath((string) $messageData->mediaStoragePath);
        $decryptor->setTextPath((string) $messageData->textStoragePath);
        $decryptor->setDecryptedMediaPath((string) $messageData->decryptedMediaStoragePath);
        $decryptor->setMetaData($metaData);

        $decryptor->decrypt();

    }
}
