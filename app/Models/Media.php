<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'original_name',
        'path',
    ];

    // Relationships

    /** @return BelongsTo<Message, Media> */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'messages_id', 'id');
    }
}
