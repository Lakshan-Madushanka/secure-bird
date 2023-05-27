<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\MediaData;
use App\Data\MessageData;
use App\Models\Media;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;
use Zip;

class DownloadMediaAction
{
    public function execute(string $messageId): \STS\ZipStream\ZipStream|bool
    {
        if ( ! $this->mediaExists($messageId)) {
            return false;
        }

        return Zip::create('media.zip', $this->getNames($messageId)->toArray());
    }

    public function mediaExists(string $messageId): bool
    {
        $message = Message::findOrFail($messageId);
        $messageData = MessageData::from($message);

        $media = $message->media;

        return ! ($media->isEmpty())



        ;
    }
    /**
     * @param  string  $messageId
     * @return \Illuminate\Support\Collection<string, string|null>
     */
    public function getNames(string $messageId): \Illuminate\Support\Collection
    {
        $message = Message::findOrFail($messageId);
        $messageData = MessageData::from($message);

        $media = $message->media;

        $mediaData = $media->mapWithKeys(function (Media $media) {
            $mediaData = MediaData::from($media);

            return [$mediaData->name => $mediaData->original_name];
        });

        return collect(Storage::files($messageData->decryptedMediaStoragePath))->mapWithKeys(fn ($value) => [Storage::path($value) => $mediaData[basename($value)]]);
    }
}
