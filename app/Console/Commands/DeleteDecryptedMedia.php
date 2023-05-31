<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\DeleteDecryptedMediaAction;
use Illuminate\Console\Command;

class DeleteDecryptedMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:delete-decrypted-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete decrypted media that has visited longer than 1h';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        app(DeleteDecryptedMediaAction::class)->execute();
    }
}
