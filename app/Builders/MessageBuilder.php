<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @template TModelClass of Message
 * @extends Builder<Message>
 */
class MessageBuilder extends Builder
{
    /**
     * @return MessageBuilder<Message>
     */
    public function valid(): MessageBuilder
    {
        return $this->notExpired()->visitsNotExceeded();
    }

    /**
     * @return MessageBuilder<Message>
     */
    public function expired(): MessageBuilder
    {
        return $this->where('expires_at', '<=', now());
    }

    /**
     * @return MessageBuilder<Message>
     */
    public function notExpired(): MessageBuilder
    {
        return $this->where('expires_at', '>', now());
    }

    /**
     * @return MessageBuilder<Message>
     */
    public function visitsNotExceeded(): MessageBuilder
    {
        return $this->whereHas('visits', function (Builder $query): void {
            $query->select(DB::raw('count(*) as visits_count'))
                ->havingRaw('no_of_allowed_visits >= visits_count');
        });
    }
}
