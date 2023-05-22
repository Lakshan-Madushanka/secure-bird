<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\MessageData;
use App\Jobs\ProcessMessageEncryption;
use App\Models\Message;
use Illuminate\Http\UploadedFile;

class StoreMessageAction
{
    public function __construct(
        private readonly UploadMediaAction $uploadMediaAction,
        private readonly EncryptMessageAction $encryptMessageAction
    ) {
    }

    /**
     * @param  MessageData  $data
     * @param  array<UploadedFile>  $media
     * @return Message
     */
    public function execute(MessageData $data, array $media = []): Message
    {
        $message = Message::create(
            $data->only(
                'no_of_allowed_visits',
                'expires_at',
                'password'
            )
                ->toArray()
        );

        $newMessageData = MessageData::from($message->refresh());

        $this->uploadMediaAction->execute($media, $message);

        ProcessMessageEncryption::dispatch(
            $this->encryptMessageAction,
            (string) $newMessageData->id,
            (string) $data->text,
            (string) $newMessageData->storagePath,
            (string) $newMessageData->textStoragePath,
            (string) $newMessageData->mediaStoragePath
        );

        return $message->refresh();
    }
}
