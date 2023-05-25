<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\Encryptor;
use Exception;

class EncryptMessageAction
{
    /**
     * @param  string  $messageId
     * Relative path to storage
     * @param  string  $text
     * @param  string  $storagePath
     * @param  string  $textStoragePath
     * Relative path to storage
     * @param  string  $mediaStoragePath
     * @return void
     * @throws Exception
     */
    public function execute(
        string $messageId,
        string $text,
        string $storagePath,
        string $textStoragePath,
        string $mediaStoragePath
    ): void {
        $encryptor = Encryptor::create($messageId, $storagePath);
        $encryptor->setText($text);
        $encryptor->setTextPath($textStoragePath);
        $encryptor->setMediaPath($mediaStoragePath);
        $encryptor->encrypt();
    }
}
