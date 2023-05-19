<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\MessageData;
use App\Models\Message;
use Illuminate\Http\UploadedFile;

class StoreMessageAction
{
    /**
     * @param  MessageData  $data
     * @param  array<UploadedFile>  $media
     * @return Message
     */
    public function execute(MessageData $data, array $media = []): Message
    {
        $message =  Message::create($data->except('text', 'id', 'created_at', 'storagePath')->toArray());

        $storeMediaAction = app(UploadMediaAction::class);

        $storeMediaAction->execute($media, $message);

        return  $message->load('media');
    }
}
