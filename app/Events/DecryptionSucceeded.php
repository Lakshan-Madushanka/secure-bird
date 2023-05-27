<?php

declare(strict_types=1);

namespace App\Events;

use App\Data\DecryptedMessageData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DecryptionSucceeded implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $id, public DecryptedMessageData $data)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("message.{$this->id}"),
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->data->text,
        ];
    }

}
