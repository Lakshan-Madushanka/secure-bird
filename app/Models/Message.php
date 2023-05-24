<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\MessageBuilder;
use App\Data\MessageData;
use Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\URL;
use Spatie\LaravelData\WithData;

/**
 * @method MessageData getData()
 * @property string $storagePath
 * @property string $textStoragePath
 * @property string $mediaStoragePath
 * @property string $url
 */
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
        'reference_mail',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'encryption_success' => 'bool'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * @param Builder $query
     * @return MessageBuilder<Message>
     */
    public function newEloquentBuilder($query): MessageBuilder
    {
        return new MessageBuilder($query);
    }

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

    /**
     * @return Attribute<string, never>
     */
    public function storagePath(): Attribute
    {
        return Attribute::make(
            get: fn () => "message/{$this->id}/"
        );
    }

    /**
     * @return Attribute<string, never>
     */
    public function textStoragePath(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->storagePath}text"
        );
    }

    /**
     * @return Attribute<string, never>
     */
    public function mediaStoragePath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->storagePath.'media'
        );
    }

    /**
     * @return Attribute<string, never>
     */
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn () => URL::to($this->id)
        );
    }

    // Relationships

    /** @return HasMany<Visit> */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'message_id', 'id');
    }

    /** @return HasMany<Media> */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'message_id', 'id');
    }
}
