<?php

declare(strict_types=1);

namespace App\Actions;

use App\Helpers\File;
use App\Models\Message;
use Illuminate\Http\UploadedFile;

class UploadMediaAction
{
    /**
     * @param  array<UploadedFile>  $media
     * @param  Message  $message
     * @return void
     */
    public function execute(array $media, Message $message): void
    {
        if ( ! $media) {
            return;
        }

        collect($media)->each(function (UploadedFile $file) use ($message): void {
            $fileName = File::sanitizeName($file->getClientOriginalName());
            $uploadedFile = $file->store($message->mediaStoragePath);

            $message->media()->create([
                'name' => $file->hashName(),
                'original_name' => $file->getClientOriginalName(),
                'full_path' => $uploadedFile,
            ]);
        });

    }
}
