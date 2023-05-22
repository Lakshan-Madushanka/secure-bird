<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\EncryptMessageAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageEncryption implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private EncryptMessageAction $encryptMessageAction,
        private string $messageId,
        private string $storagePath,
        private string $textStoragePath,
        private string $mediaStoragePath
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->encryptMessageAction->execute(
            $this->messageId,
            $this->storagePath,
            $this->textStoragePath,
            $this->mediaStoragePath,
        );
    }
}
