<?php

namespace App\Actions;

use App\Mail\MessageVisitCompleted;
use App\Mail\MessageVisited;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;

class SendMessageVisitCompletedMailAction
{
    public function execute(Message $message): void
    {
        $message->load([
                'visits' => function ($query) {
                    $query->latest();
                },
            ]);

        if ($message?->reference_mail && $message->inValid()->exists()) {
            Mail::to($message->reference_mail)->send(new MessageVisitCompleted($message));
        }
    }
}
