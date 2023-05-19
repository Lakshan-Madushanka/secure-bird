<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Message;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class MessageData extends Data
{
    public function __construct(
        public ?string $id,
        public string $text,
        public ?string $password,
        public int $no_of_allowed_visits,
        public Carbon|CarbonImmutable|null $expires_at,
        public Lazy|string|null $userTimeZone,
        public int $encryption_progress,
        public ?Carbon $created_at,
        public ?string $storagePath
    ) {
    }

    /**
     * @param  array{text: string, password: string, no_of_allowed_visits: int|string, expires_at?: string, userTimeZone: string, encryption_progress?: int}  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $expiresAt = null;

        if (isset($data['expires_at'])) {
            $expiresAt = self::createExpiresAt($data['expires_at'], $data['userTimeZone']);
        }

        return new self(
            id: null,
            text: $data['text'],
            password: $data['password'],
            no_of_allowed_visits: $data['no_of_allowed_visits'] !== '' ? (int) $data['no_of_allowed_visits'] : -1,
            expires_at: $expiresAt,
            userTimeZone: Lazy::create(fn () => $data['userTimeZone']),
            encryption_progress: $data['encryption_progress'] ?? 0,
            created_at: null,
            storagePath: null
        );
    }

    public static function fromModel(Message $message): self
    {
        return new self(
            id: $message->id,
            text: $message->text ?? "",
            password: $message->password,
            no_of_allowed_visits: $message->no_of_allowed_visits,
            expires_at: $message->created_at,
            userTimeZone: null,
            encryption_progress: $message->encryption_progress,
            created_at: $message->created_at,
            storagePath: $message->storagePath
        );
    }

    public static function createExpiresAt(string $expiresAt, string $timeZone): Carbon|CarbonImmutable
    {
        return Carbon::parse($expiresAt)->shiftTimezone($timeZone)->timezone('utc');
    }
}
