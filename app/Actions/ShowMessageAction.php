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

        ProcessMessageDecryption::dispatch($this->decryptMessageAction, $messageId, $this->getMetaData());
    }

    /** @return array<string, string|null> */
    public function getMetaData(): array
    {
        $request = request();

        return [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];
    }
}
