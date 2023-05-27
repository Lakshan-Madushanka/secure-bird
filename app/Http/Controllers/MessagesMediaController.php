<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DownloadMediaAction;

class MessagesMediaController extends Controller
{
    public function download(string $messageId): bool|\STS\ZipStream\ZipStream
    {
        return app(DownloadMediaAction::class)->execute($messageId);
    }
}
