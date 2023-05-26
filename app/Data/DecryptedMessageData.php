<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class DecryptedMessageData extends Data
{
    public function __construct(
        public ?string $text,
        public ?string $decrptedMediaPath
    ) {
    }
}
