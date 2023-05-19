<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class MediaData extends Data
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $original_name,
        public string $full_path,
        public Carbon $created_at,
    ) {
    }
}
