<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\DeleteInvalidMessagesAction;
use Illuminate\Console\Command;

class DeleteInvalidMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:delete-invalid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will remove all invalid messages (expired or allowed no of visits exceeded) !';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Messages are being deleted...");

        $noOfDeletedMessages = app(DeleteInvalidMessagesAction::class)->execute();

        $this->info("{$noOfDeletedMessages} messages deleted");
    }
}
