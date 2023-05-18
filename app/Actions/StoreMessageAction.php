<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\MessageData;
use App\Models\Message;

class StoreMessageAction
{
    public function execute(MessageData $data): Message
    {
        return Message::create($data->all());
    }
}
