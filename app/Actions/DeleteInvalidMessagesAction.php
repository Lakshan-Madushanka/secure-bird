<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Message;

class DeleteInvalidMessagesAction
{
    public function execute(): int
    {
        $ids = Message::query()->inValid()->pluck('id');

        return Message::destroy($ids);
    }
}
