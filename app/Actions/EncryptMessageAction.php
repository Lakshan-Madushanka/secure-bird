<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\Encryptor;

class EncryptMessageAction
{
    public function __construct()
    {
    }

    /**
     * @param  string  $messageId
     * Relative path to storage
     * @param  string  $textStoragePath
     * Relative path to storage
     * @param  string  $mediaStoragePath
     * @return void
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
