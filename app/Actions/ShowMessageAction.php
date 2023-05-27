<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\ProcessMessageDecryption;
use App\Models\Message;
use Illuminate\Validation\ValidationException;

class ShowMessageAction
{
    public function __construct(
        private readonly CheckPasswordAction $checkPasswordAction,
        private readonly DecryptMessageAction $decryptMessageAction,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function execute(string $password, string $messageId): void
    {
        $messageData = Message::findOrFail($messageId)->getData();

        $this->checkPasswordAction->execute($password, (string) $messageData->password);

        $this->decryptMessageAction->execute($messageId);

        ProcessMessageDecryption::dispatch($this->decryptMessageAction, $messageId);

    }
}
