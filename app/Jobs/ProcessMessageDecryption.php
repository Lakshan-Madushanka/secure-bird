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
     * Create a new job instance.
     */
    public function __construct(
        private DecryptMessageAction $decryptMessageAction,
        private string $messageId,
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
        );
    }
}
