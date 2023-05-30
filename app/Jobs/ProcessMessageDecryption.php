<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\DecryptMessageAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageDecryption implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  DecryptMessageAction $decryptMessageAction
     * @param  string $messageId
     * @param  array<string, string|null> $metaData
     */
    public function __construct(
        private readonly DecryptMessageAction $decryptMessageAction,
        private readonly string $messageId,
        private readonly array $metaData
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(1);

        $this->decryptMessageAction->execute(
            $this->messageId,
            $this->metaData,
        );
    }
}
