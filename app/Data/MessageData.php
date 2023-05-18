<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class MessageData extends Data
{
    public function __construct(
        public string $text,
        public ?string $password,
        public int $no_of_allowed_visits,
        public Carbon|CarbonImmutable|null $expires_at,
        public Lazy|string|null $userTimeZone,
        public int $encryption_progress = 0,
    ) {
    }

    /**
     * @param  array{text: string, password: string, no_of_allowed_visits: int, expires_at?: string, userTimeZone: string, encryption_progress?: int}  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $expiresAt = null;

        if (isset($data['expires_at'])) {
            $expiresAt = self::createExpiresAt($data['expires_at'], $data['userTimeZone']);
        }

        return new self(
            text: $data['text'],
            password: $data['password'],
            no_of_allowed_visits: (int) $data['no_of_allowed_visits'],
            expires_at: $expiresAt,
            userTimeZone: Lazy::create(fn () => $data['userTimeZone']),
            encryption_progress: $data['encryption_progress'] ?? 0,
        );
    }

    public static function createExpiresAt(string $expiresAt, string $timeZone): Carbon|CarbonImmutable
    {
        return Carbon::parse($expiresAt)->shiftTimezone($timeZone)->timezone('utc');
    }
}
