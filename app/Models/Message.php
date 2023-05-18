<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\MessageData;
use Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelData\WithData;

class Message extends Model
{
    use HasFactory;
    use HasUuids;
    use WithData;

    protected string $dataClass = MessageData::class;

    protected $fillable = [
        'text',
        'password',
        'no_of_allowed_visits',
        'encryption_progress',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    // Accessors and mutators

    /**
     * @return Attribute<never, string>
     */
    public function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value)
        );
    }

    // Relationships

    /** @return HasMany<Visit> */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'message_id', 'id');
    }

    /** @return HasMany<Media> */
    public function medias(): HasMany
    {
        return $this->hasMany(Media::class, 'message_id', 'id');
    }
}
