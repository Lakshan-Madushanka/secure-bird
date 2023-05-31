<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\VisitData;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DeleteDecryptedMediaAction
{
    public function execute(): void
    {
        Message::query()
            ->where('encryption_success', true)
            ->with('latestVisit')
            ->chunkById(100, function (Collection $messages): void {
                $messages->each(function (Model $message, string $key): mixed {
                    // We need to delete decrypted medias that has visits longer than 1h
                    /** @var Message $message */
                    $messageData = $message->getData();
                    /** @var VisitData $visitData */
                    $visitData = $messageData->latestVisit;
                    if ($visitData->created_at->diffInMinutes(now()) > 60) {
                        Storage::deleteDirectory((string) $messageData->decryptedMediaStoragePath);
                    }
                });
            });
    }
}
