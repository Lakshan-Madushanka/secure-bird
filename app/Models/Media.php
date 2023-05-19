<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\MediaData;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

/**
 * @method MediaData getData()
 */
class Media extends Model
{
    use HasFactory;
    use HasUuids;
    use WithData;

    protected string $dataClass = MediaData::class;

    protected $fillable = [
        'name',
        'original_name',
        'full_path',
    ];

    // Relationships

    /** @return BelongsTo<Message, Media> */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'messages_id', 'id');
    }
}
