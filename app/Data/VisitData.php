<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class VisitData extends Data
{
    public function __construct(
        public string $message_id,
        public string $ip_address,
        public string $user_agent,
        public Carbon $created_at,
    ) {
    }
}
