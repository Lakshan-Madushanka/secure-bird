<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DownloadMediaAction;
use STS\ZipStream\ZipStream;

class MessagesMediaController extends Controller
{
    public function download(string $messageId): bool|ZipStream
    {
        return app(DownloadMediaAction::class)->execute($messageId);
    }
}
