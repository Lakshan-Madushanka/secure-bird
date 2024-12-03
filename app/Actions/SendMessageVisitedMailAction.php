<?php

declare(strict_types=1);

namespace App\Actions;

use App\Mail\MessageVisited;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;

class SendMessageVisitedMailAction
{
    public function execute(Message $message): void
    {
        $message->load('visits');

        if ($message?->reference_mail) {
            Mail::to($message->reference_mail)->send(new MessageVisited($message));
        }
    }
}
