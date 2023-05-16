<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'text',
        'password',
        'no_of_allowed_visits',
        'encryption_progress',
        'expires_at',
    ];

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
